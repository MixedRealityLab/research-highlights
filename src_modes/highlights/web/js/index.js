
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

var curListView = '';
var curType = '';
var prevHash = '';

// from http://stackoverflow.com/questions/1144783/replacing-all-occurrences-of-a-string-in-javascript
function replaceAll(find, replace, str) {
	return str.replace(new RegExp(find, 'g'), replace);
}

// load a page
function loadPage (handler, hash, data) {
	ReHi.sendData({
		dataType: 'json',
		data: data,
		url: '@@@URI_ROOT@@@/do/' + handler,
		type: 'post',
		success: function (response, textStatus, jqXHR) {
					showSubmissions(response);
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

	if (curKeywords.indexOf(keyword) > -1) {
		curKeywords = curKeywords.replace(keyword + ',', '');
	} else {
		curKeywords += keyword + ',';
	}

	window.location.hash = '#keywords=' + curKeywords;
	firstResponder('#keywords=' + curKeywords);
}

// change the sidebar list view
function changeListView (list, onCompleteFn) {
	if(list == curListView) {
		if(onCompleteFn != undefined) {
			onCompleteFn();
		}
		return;
	}

	curListView = list;

	completeFn = function() {
		$('a').click(function(e) {
			if (($(this).hasClass('loadPage') || $(this).parents('.loadPage').length > 0) && this.hash.charAt(0) == '#') {
    			e.preventDefault();
				window.location.hash = this.hash;
				firstResponder(this.hash);
    		}
    	});

		if(onCompleteFn != undefined) {
			onCompleteFn();
		}
	}

	$('a.listMode').removeClass('selected');
	$('a[data-listmode="' + list + '"]').addClass('selected');

	if (list == 'cohort') {
		ReHi.sendData({
			dataType: 'json',
			url: '@@@URI_ROOT@@@/do/cohorts',
			type: 'post',
			success: function (response, textStatus, jqXHR) {
		  				var html = '<div class="panel-body"><ul class="list-group subgroup">';
		  		 		for (var i = 0; i < response.length; i++) {
		  		 			var cohort = response[i];
		  		 			html += '<li class="list-group-item viewItem loadPage"><a href="#cohort=' + cohort + '">' + cohort + ' Cohort</a></li>';
						}
						$('#viewList').html(html + '</ul></div>');
						completeFn();
					}
		});
	} else if (list == 'name') {
		ReHi.sendData({
			dataType: 'json',
			url: '@@@URI_ROOT@@@/do/submitted',
			type: 'post',
			success: function (response, textStatus, jqXHR) {
		  				$('#viewList').empty();
		  				var prevCohort = '', addCohort = '';
		  		 		for (var i = 0; i < response.length; i++) {
		  		 			var user = response[i];
		  		 			if(prevCohort != '' && user.cohort != prevCohort) {
		  		 				$('#viewList').append(addCohort + '</ul></div></div></div>');
		  		 				addCohort = '';
		  		 			}
		  		 			if(prevCohort == '' || user.cohort != prevCohort) {
								addCohort += '<div class="panel panel-default"><div class="panel-heading" role="tab" id="cohort-' + user.cohort + '"><h4 class="panel-title">';
								addCohort += '<a data-toggle="collapse" data-parent="#viewList" href="#cohort-' + user.cohort + '-links" aria-expanded="true" aria-controls="cohort-' + user.cohort + '-links">' +  user.cohort + ' Cohort';
								addCohort += '</a></h4></div><div id="cohort-' + user.cohort + '-links" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cohort-' + user.cohort + '"><div class="panel-body"><ul class="list-group subgroup">';
		  		 				prevCohort = user.cohort;
		  		 			}
		  		 			addCohort += '<li class="list-group-item viewItem loadPage"><a href="#read=' + user.username + '">' + user.firstName + ' ' + user.surname + '</a></li>';
						}
		  		 		$('#viewList').append(addCohort + '</ul></li>');
						completeFn();
					}
		});
	} else if (list == 'keyword') {
		ReHi.sendData({
			dataType: 'json',
			url: '@@@URI_ROOT@@@/do/keywords',
			type: 'post',
			success: function (response, textStatus, jqXHR) {
		  				var html = '<div class="keywordSidebar">';
		  				var colours = ["primary", "success", "info", "warning", "danger"];
		  		 		for (var i = 0; i < response.length; i++) {
		  		 			var data = response[i];
		  		 			var cleanVal = replaceAll(' ', '_', data.name);
		  		 			var size = 1 * data.weight; if(size < .7) size = .7;
		  		 			html += '<a href="" data-keyword="' + cleanVal + '" class="toggleKeyword label label-onlyHover label-' + colours[i % colours.length] + '" style="font-size: ' + size + 'em;" id="keyword-' + cleanVal + '">' + data.name + '</a> ';
						}
						$('#viewList').html(html + '</div>');
						$('.toggleKeyword').click(function(e) { e.preventDefault(); toggleKeyword( $(e.target).data('keyword')) });
						completeFn();
					}
		});
	}
}

// show loaded submissions
function showSubmissions(response) {
	$('.read').empty();
	$('.headerOnly').unbind('click.headerOnly');

	var headersOnly = response.length > 1;
	for(var i = 0; i < response.length; i++) {
		var data = response[i];
		if(data.length != 0) {
			var $content = $('<div></div>').addClass('row-fluid');

			// header
			var $submission = $('<section></section>');

			var linkedTitle = headersOnly ? 'headerOnly loadPage" href="#read=' + data.username + '"' : '';

			var header = [];
			header.push($('<h1 class="' + linkedTitle + '"> ' + data.title + '</h1>'));

			if(!headersOnly) {
				header.push($('<span class="name"></span>').text(data.firstName + ' ' + data.surname + ' (' + data.cohort + ' cohort)'));
				if (data.twitter != '')
					header.push($('<span class="twitter"><span class="glyphicon glyphicon-user"></span><a href="https://twitter.com/' + data.twitter.substring(1) + '">' + data.twitter + '</a></span>'));
				if (data.website != '') {
					var visible = data.website.replace(/(http|https):\/\//, '');
					if(visible.slice(-1) == '/') {
						visible = visible.substring(0, visible.length - 1);
					}
					header.push($('<span class="website"><span class="glyphicon glyphicon-home"></span><a href="' + data.website + '">' + visible + '</a></span></span>'));
				}
			}

			if(data.keywords != undefined) {
				var colours = ["primary", "success", "info", "warning", "danger"]; var colK = 0;
				var kwHtml = '<span class="keywords' + linkedTitle + '">';
				var keywords = data.keywords.split(',');
				for(var k = 0; k < keywords.length; k++) {
					colK = k % colours.length;
					kwHtml += ' <span class="label label-noColour label-noHover ' + linkedTitle + '">' + keywords[k] + '</span>';
				}
			}
			header.push($(kwHtml + '</span>'));
			var $header = $('<div class="well ' + (headersOnly ? 'headerOnly ' + linkedTitle : '') + '"></div>').html(header);

			// article
			if(!headersOnly) {
				var $article = $('<article></article>');
				var $body = $('<div></div>').addClass('body').html(data.html);
				var $fundingStatement = $('<small></small>').addClass('body');
				
				if(data.industryName != '') {
					var industry = ' and ' + data.industryName + ' (' + (data.industryUrl == '' ? '(no website)' : data.industryUrl.replace(/(http|https):\/\//, '')) + ').';
					$fundingStatement.html(data.fundingStatement + industry);
				} else {
					$fundingStatement.html(data.fundingStatement + '.');
				}

				$article.html([$body, $fundingStatement]);

				$submission.append([$header, $article]);
			} else {
				$submission.append($header);
			}
			$('.read').append($submission);
		}
	}
	$('.row-offcanvas').toggleClass('active');
	$('.headerOnly.loadPage').bind('click.headerOnly', function(e) {
		e.preventDefault();
		window.location.hash = $(this).attr('href');
		firstResponder(window.location.hash);
	});
}

// Page event handling
function firstResponder(hash) {
	if(hash == '') {
		hash = '#home';
	}
	if(hash == prevHash) {
		return;
	}
	prevHash = hash;

	if (hash.indexOf ('#cohort') == 0) {
		curType = 'cohort';
		$('.jumbotron').remove();

		changeListView ('cohort', function() {
			$('.loadPage.selected').removeClass('selected');
			$('a[href="' + hash + '"]').parents('.loadPage').addClass('selected');

			loadPage ('read', hash, 'cohort=' + hash.replace ('#cohort=', ''));
		});
	
	} else if (hash.indexOf ('#read') == 0) {
		curType = 'read';
		$('.jumbotron').remove();

		changeListView ('name', function() {
			$('.loadPage.selected').removeClass('selected');

			var $item = $('a[href="' + hash + '"]');
			$item.parents('.loadPage').addClass('selected');
			$item.parents('.panel-collapse').collapse();

			loadPage ('read', hash, 'user=' + hash.replace ('#read=', ''));
		});
	} else if (hash.indexOf ('#keywords') >= 0) {
		curType = 'keyword';
		$('.jumbotron').remove();

		changeListView ('keyword', function() {
			var keywords = hash.replace ('#keywords=', '').split(',');
			
			$('.toggleKeyword').each(function(i,elem) {
				if ($.inArray($(elem).data('keyword'), keywords) > -1) {
					$(elem).addClass('label-selected');
				} else {
					$(elem).removeClass('label-selected');
				}
			});

			loadPage ('read', hash, 'keywords=' + hash.replace ('#keywords=', ''));
		});
	} else {
		changeListView('name');
	}
}


$(function() {
	$('[data-toggle=offcanvas]').click(function() {
		$('.row-offcanvas').toggleClass('active');
	});

	$('.listMode').click(function(e) {
		e.preventDefault();
		$('.listMode').each(function(k,v) {
			if (v == e.target) {
				$(v).addClass('selected');
				changeListView($(v).data('listmode'));
			} else {
				$(v).removeClass('selected');
			}
		})
	});

	ReHi.fadePageIn();

	firstResponder(window.location.hash);
});
