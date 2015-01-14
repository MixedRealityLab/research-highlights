
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

function loadPage(e) {
	var type = $(e.target).data('type');
	if(type == 'cohort') {
		showCohort($(e.target).data('val'));
		$('.viewItem').removeClass('selected');
		$(e.target).parent().addClass('selected');
	} else if(type == 'name') {
		showUser($(e.target).data('val'));
		$('.viewItem').removeClass('selected');
		$(e.target).parent().addClass('selected');
	}
}

function showList(list) {
	switch(list) {
		case 'cohort':
			ReHi.sendData({
				dataType: 'json',
				url: '@@@URI_ROOT@@@/do/cohorts',
				type: 'post',
				success: function (response, textStatus, jqXHR) {
			  				$('#viewList').empty();
			  		 		for (var i = 0; i < response.length; i++) {
			  		 			var cohort = response[i];
			  		 			$('#viewList').append('<li><a href="#" data-type="cohort" data-val="' + cohort + '" class="loadPage">' + cohort + ' Cohort</a></li>');
							}
							$('.loadPage').bind('click.loadPage', function(e) { loadPage(e) });
						}
			});
			break;

		case 'name':
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
			  		 			addCohort += '<li class="list-group-item viewItem"><a href="#" data-type="name" data-val="' + user.username + '" class="loadPage">' + user.firstName + ' ' + user.surname + '</a></li>';
							}
			  		 		$('#viewList').append(addCohort + '</ul></li>');
			  		 		$('.loadPage').bind('click.loadPage', function(e) { loadPage(e); });
						}
			});
			break;

		case 'keyword':
			$('#viewList').empty();
			$('#viewList').text('keywords');
			break;
	}
}

function showSubmissions(response) {
	$('.read').empty();

	var headersOnly = response.length > 1;
	for(var i = 0; i < response.length; i++) {
		var data = response[i];
		if(data.length != 0) {
			var $content = $('<div></div>').addClass('row-fluid');

			// header
			var $submission = $('<section></section>');

			var linkedTitle = headersOnly ? 'headerOnly loadPage" data-type="name" data-val="' + data.username : '';

			var header = [];
			header.push($('<h1 class="' + linkedTitle + '"> ' + data.title + '</h1>'));

			if(!headersOnly) {
				header.push($('<span class="name"></span>').text(data.firstName + ' ' + data.surname + ' (' + data.cohort + ' cohort)'));
				if (data.twitter != '')
					header.push($('<span class="twitter"><span class="glyphicon glyphicon-user"></span>' + data.twitter + '</span>'));
				if (data.website != '') {
					var visible = data.website.replace(/(http|https):\/\//, '');
					if(visible.slice(-1) == '/') {
						visible = visible.substring(0, visible.length - 1);
					}
					header.push($('<span class="website"><span class="glyphicon glyphicon-home"></span><a href="' + data.website + '">' + visible + '</a></span></span>'));
				}
			}

			if(data.keywords != undefined) {
				var colours = ["default", "primary", "success", "info", "warning", "danger"]; var colK = 0;
				var kwHtml = '<span class="keywords' + linkedTitle + '">';
				var keywords = data.keywords.split(',');
				for(var k = 0; k < keywords.length; k++) {
					colK = k % colours.length;
					kwHtml += ' <span class="label label-' + colours[colK] + ' ' + linkedTitle + '">' + keywords[k] + '</span>';
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

				$submission.hide().append([$header, $article]);
			} else {
				$submission.hide().append($header);
			}
			$('.read').append($submission);
		}
	}
	$('.read *').fadeIn();
	$('.row-offcanvas').toggleClass('active');

	$('.loadPage').unbind('click.loadPage');
	$('.loadPage').bind('click.loadPage', function(e) { loadPage(e) });
}

function showCohort(cohort) {
	ReHi.sendData({
		dataType: 'json',
		data: 'cohort=' + cohort,
		url: '@@@URI_ROOT@@@/do/read',
		type: 'post',
		success: function (response, textStatus, jqXHR) {
					$('.jumbotron').hide();
					console.log(response);
					showSubmissions(response);
					window.location.hash="cohort-" + cohort;
				}
	});
}

function showUser(user) {
	ReHi.sendData({
		dataType: 'json',
		data: 'user=' + user,
		url: '@@@URI_ROOT@@@/do/read',
		type: 'post',
		success: function (response, textStatus, jqXHR) {
					$('.jumbotron').hide();
					showSubmissions(response);
					window.location.hash="read-" + user;
				}
	});
}

$(function() {
	ReHi.fadePageIn();

	$('[data-toggle=offcanvas]').click(function() {
		$('.row-offcanvas').toggleClass('active');
	});


	$('.listMode').click(function(e) {
		$('.loadPage').unbind('click.loadPage');
		$('.listMode').each(function(k,v) {
			if (v == e.target) {
				$(v).addClass('selected');
				showList($(v).data('listmode'));
			} else {
				$(v).removeClass('selected');
			}
		})
	});

	showList($('.listMode.selected').data('listmode'));

});