
/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

var curListView = '';
var curType = '';
var prevHash = '';

// from http://stackoverflow.com/questions/1144783/replacing-all-occurrences-of-a-string-in-javascript
function replaceAll (find, replace, str) {
	return str.replace (new RegExp (find, 'g'), replace);
}

// load a page
function loadPage (handler, hash, data, showErrorFn, title, showLoading) {
	if (showLoading == undefined) {
		showLoading = false;
	}

	ReHi.sendData ({
		dataType: 'json',
		data: data,
		url: '@@@URI_ROOT@@@/do/' + handler,
		type: 'post',
		beforeSend: function () {
			if (showLoading) {
				$('.read').fadeOut ();
				$('.loading').fadeIn ();
			}
		},
		complete: function () {
			if (showLoading) {
				$('.loading').fadeOut ();
				$('.read').fadeIn ();
			} else if ($('.read').hasClass ('collapse')) {
				$('.read').fadeIn ();
			}
		},
		success: function (response, textStatus, jqXHR) {
			if (response.length == 0) {
				showErrorFn (response);
			} else {
				showSubmissions (response, title);
			}
		}
	});
}

// toggle keywords
function toggleKeyword (keyword) {
	var hash = window.location.hash;
	var curKeywords = '';

	if (hash.indexOf ('#keywords') == 0) {
		curKeywords = hash.replace ('#keywords=', '');
	}

	if (curKeywords.indexOf (keyword) > -1) {
		curKeywords = curKeywords.replace (keyword + ',', '');
	} else {
		curKeywords += keyword + ',';
	}

	window.location.hash = '#keywords=' + curKeywords;
	firstResponder ('#keywords=' + curKeywords);
}

// change the sidebar list view
function changeListView (list, onCompleteFn) {
	if (list == curListView) {
		if (onCompleteFn != undefined) {
			onCompleteFn ();
		}
		return;
	}

	curListView = list;

	completeFn = function () {
		$('a').click (function (e) {
			if (($(this).hasClass ('loadPage') || $(this).parents ('.loadPage').length > 0) && this.hash.charAt (0) == '#') {
				e.preventDefault ();
				window.location.hash = this.hash;
				firstResponder (this.hash);
			}
		});

		if (onCompleteFn != undefined) {
			onCompleteFn ();
		}
	}

	$('a.listMode').removeClass ('selected');
	if (list != 'none') {
		$('a[data-listmode="' + list + '"]').addClass ('selected');
	}

	if (list == 'cohort') {
		ReHi.sendData ({
			dataType: 'json',
			url: '@@@URI_ROOT@@@/do/cohorts',
			type: 'post',
			success: function (response, textStatus, jqXHR) {
						var html = '<div class="panel-body"><ul class="list-group subgroup">';
						for (var i = 0; i < response.length; i++) {
							var cohort = response[i];
							html += '<li class="list-group-item viewItem loadPage"><a href="#cohort=' + cohort + '">' + cohort + ' Cohort</a></li>';
						}
						$('#viewList').html (html + '</ul></div>');
						completeFn ();
					}
		});
	} else if (list == 'name') {
		ReHi.sendData ({
			dataType: 'json',
			url: '@@@URI_ROOT@@@/do/submitted',
			type: 'post',
			success: function (response, textStatus, jqXHR) {
						$('#viewList').empty ();
						var prevCohort = '', addCohort = '';
						for (var i = 0; i < response.length; i++) {
							var user = response[i];
							if (prevCohort != '' && user.cohort != prevCohort) {
								$('#viewList').append (addCohort + '</ul></div></div></div>');
								addCohort = '';
							}
							if (prevCohort == '' || user.cohort != prevCohort) {
								addCohort += '<div class="panel panel-default"><div class="panel-heading pageGroup" role="tab" id="cohort-' + user.cohort + '"><h4 class="panel-title">';
								addCohort += '<a data-toggle="collapse" data-parent="#viewList" href="#cohort-' + user.cohort + '-links" aria-expanded="true" aria-controls="cohort-' + user.cohort + '-links">' +  user.cohort + ' Cohort';
								addCohort += '</a></h4></div><div id="cohort-' + user.cohort + '-links" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cohort-' + user.cohort + '"><div class="panel-body"><ul class="list-group subgroup">';
								prevCohort = user.cohort;
							}
							addCohort += '<li class="list-group-item viewItem loadPage"><a href="#read=' + user.username + '">' + user.firstName + ' ' + user.surname + '</a></li>';
						}
						$('#viewList').append (addCohort + '</ul></li>');
						completeFn ();
					}
		});
	} else if (list == 'keyword') {
		ReHi.sendData ({
			dataType: 'json',
			url: '@@@URI_ROOT@@@/do/keywords',
			type: 'post',
			success: function (response, textStatus, jqXHR) {
						var html = '<div class="keywordSidebar">';
						var colours = ["primary", "success", "info", "warning", "danger"];
						var i = 0;
						$.each (response, function (k,v) {
							var cleanVal = replaceAll (' ', '%20', k);
							html += '<a href="" data-keyword="' + k + '" class="toggleKeyword label label-onlyHover label-' + colours[i++ % colours.length] + '"  id="keyword-' + cleanVal + '">' + k + '</a> ';
						});
						$('#viewList').html (html + '</div>');
						$('.toggleKeyword').click (function (e) { e.preventDefault (); toggleKeyword ( $(e.target).data ('keyword')) });
						completeFn ();
					}
		});
	} else {
		$('#viewList').html ('');
		completeFn ();
	}
}

// show error message
function showError (title, text) {
	$('.read').empty ();
	$('.headerOnly').unbind ('click.headerOnly');

	var $submission = $('<section></section>');
	$submission.append ([$('<h1 class="pagetitle">' + title + '</h1>'),$('<p></p>').addClass ('error').html (text)]);
	$('.read').append ($submission);

	$('.row-offcanvas').toggleClass ('active');
}

// show loaded submissions
function showSubmissions (response, title) {
	$('.read').empty ();
	$('.headerOnly').unbind ('click.headerOnly');

	var headersOnly = response.length > 1 || response[0].html == undefined;

	if (title != undefined) {
		$('.read').append ($('<h1 class="pagetitle"></h1>').html (title));
	}

	for (var i = 0; i < response.length; i++) {
		var data = response[i];
		if (data.length != 0) {
			// header
			var $submission = $('<section></section>');

			link = data.saveAs;
			if (link == undefined) {
				link = data.username;
			}

			var linkedTitle = headersOnly ? 'headerOnly loadPage" href="#read=' + data.saveAs + '"' : '';

			var header = [];
			header.push ($('<h1 class="' + linkedTitle + '"> ' + data.title + '</h1>'));

			if (!headersOnly) {
				header.push ($('<span class="name"></span>').text (data.firstName + ' ' + data.surname + ' (' + data.cohort + ' cohort)'));
				if (data.twitter != '')
					header.push ($('<span class="twitter"><span class="glyphicon glyphicon-user"></span><a href="https://twitter.com/' + data.twitter.substring (1) + '">' + data.twitter + '</a></span>'));
				if (data.website != '') {
					var visible = data.website.replace (/(http|https):\/\//, '');
					if (visible.slice (-1) == '/') {
						visible = visible.substring (0, visible.length - 1);
					}
					header.push ($('<span class="website"><span class="glyphicon glyphicon-home"></span><a href="' + data.website + '">' + visible + '</a></span></span>'));
				}
			}

			if (data.keywords != undefined) {
				var colours = ["primary", "success", "info", "warning", "danger"]; var colK = 0;
				var kwHtml = '<span class="keywords' + linkedTitle + '">';
				var keywords = data.keywords.split (',');
				for (var k = 0; k < keywords.length; k++) {
					colK = k % colours.length;
					kwHtml += ' <span class="label label-noColour label-noHover ' + linkedTitle + '">' + keywords[k] + '</span>';
				}
			}
			header.push ($(kwHtml + '</span>'));
			var $header = $('<div class="well ' + (headersOnly ? 'headerOnly ' + linkedTitle : '') + '"></div>').html (header);

			// article
			if (!headersOnly) {
				var $article = $('<article></article>');
				var $body = $('<div></div>').addClass ('body').html (data.html);
				var $fundingStatement = $('<small></small>').addClass ('body');

				if (data.industryName != '') {
					var industry = ' and ' + data.industryName + ' (' + (data.industryUrl == '' ? '(no website)' : data.industryUrl.replace (/(http|https):\/\//, '')) + ').';
					$fundingStatement.html (data.fundingStatement + industry);
				} else {
					$fundingStatement.html (data.fundingStatement + '.');
				}

				$article.html ([$body, $fundingStatement]);

				$submission.append ([$header, $article]);
			} else {
				$submission.append ($header);
			}
			$('.read').append ($submission);
		}
	}
	$('.row-offcanvas').toggleClass ('active');
	$('.headerOnly.loadPage').bind ('click.headerOnly', function (e) {
		e.preventDefault ();
		window.location.hash = $(this).attr ('href');
		firstResponder (window.location.hash);
	});
}

// Page event handling
function firstResponder (hash) {
	if (hash == '') {
		hash = '#home';
	}
	if (hash == prevHash) {
		return;
	}
	prevHash = hash;

	if (hash.indexOf ('#cohort') == 0) {
		curType = 'cohort';
		$('.jumbotron').fadeOut ();
		$('.featureWall').fadeOut ();

		changeListView ('cohort', function () {
			$('.loadPage.selected').removeClass ('selected');
			$('a[href="' + hash + '"]').parents ('.loadPage').addClass ('selected');

			var cohort = hash.replace ('#cohort=', '');
			loadPage ('read', hash, 'cohort=' + cohort, function () {
				showError ('Invalid cohort', 'Sorry, no articles were found for that cohort.');
			}, 'Submissions from the ' + cohort + ' Cohort');
		});
		$('#q').val ('');
	} else if (hash.indexOf ('#read') == 0) {
		curType = 'read';
		$('.jumbotron').fadeOut ();
		$('.featureWall').fadeOut ();

		changeListView ('name', function () {
			$('.loadPage.selected').removeClass ('selected');

			var $item = $('a[href="' + hash + '"]');
			$item.parents ('.loadPage').addClass ('selected');
			$item.parents ('.panel-collapse').collapse ();

			loadPage ('read', hash, 'user=' + hash.replace ('#read=', ''), function () {
				showError ('Unknown username', 'Sorry, no submission was found for that username.');
			});
		});
		$('#q').val ('');
	} else if (hash.indexOf ('#keywords') >= 0) {
		curType = 'keyword';
		$('.jumbotron').fadeOut ();
		$('.featureWall').fadeOut ();

		changeListView ('keyword', function () {
			var keywords = hash.replace ('#keywords=', '');
			if (keywords != '') {
				keywords = replaceAll ('%20', ' ', keywords).split (',');

				$('.toggleKeyword').each (function (i,elem) {
					if ($.inArray ($(elem).data ('keyword'), keywords) > -1) {
						$(elem).addClass ('label-selected');
					} else {
						$(elem).removeClass ('label-selected');
					}
				});

				if (keywords == undefined || keywords.length > 2) {
					title = 'Submissions matching the selected keywords';
				} else {
					title = 'Submissions with the keyword <em>' + keywords[0] + '</em>';
				}

				loadPage ('read', hash, 'keywords=' + hash.replace ('#keywords=', ''), function () {
					showError ('No results found :-(', 'Sorry, no submission were found for the keywords supplied.');
				}, title);
			}
		});
		$('#q').val ('');
	} else if (hash.indexOf ('#q') >= 0) {
		curType = 'search';
		$('.jumbotron').fadeOut ();
		$('.featureWall').fadeOut ();

		changeListView ('none', function () {
			$('.loadPage.selected').removeClass ('selected');
			var q = hash.replace ('#q=', '');
			$('#q').val (q);
			loadPage ('search', hash, 'q=' + q, function () {
				showError ('No results found :-(', 'Sorry, no results were found for <em>' + q + '</em>, please try refining your search terms.<br><br><em>Top tip: You can use an asterisk to search for part words, e.g. <strong>collab*</strong></em>.');
			}, 'Search results for <em>' + q + '</em>', true);

		});
	} else {
		changeListView ('name');
		$('.jumbotron').fadeIn ();
		$('.featureWall').fadeIn ();
		$('.read').fadeOut ();
	}
}


$(function () {
	ReHi.fadePageIn ();

	$('[data-toggle=offcanvas]').click (function () {
		$('.row-offcanvas').toggleClass ('active');
	});

	$('.listMode').click (function (e) {
		e.preventDefault ();
		$('.listMode').each (function (k,v) {
			if (v == e.target) {
				$(v).addClass ('selected');
				changeListView ($(v).data ('listmode'));
			} else {
				$(v).removeClass ('selected');
			}
		})
	});

	$('.search-form').submit (function (e) {
		e.preventDefault ();
		window.location.hash = '#q=' + $('#q').val ();
	});

	$('.loading').fadeOut ();

	firstResponder (window.location.hash);
	$(window).hashchange ( function (){
		firstResponder (window.location.hash);
	});

	$('button[data-toggle="offcanvas"]').click(function() {
			$('footer').toggleClass('collapse');
	});
});
