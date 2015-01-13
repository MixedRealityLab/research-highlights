
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

function showList(list) {
	switch(list) {
		case 'cohort':
			ReHi.sendData({
				dataType: 'json',
				url: '@@@URI_ROOT@@@/do/cohorts',
				type: 'post',
				success: function (response, textStatus, jqXHR) {
			  				$('#view-list').empty();
			  		 		for (var i = 0; i < response.length; i++) {
			  		 			var cohort = response[i];
			  		 			$('#view-list').append('<li><a href="#" data-type="cohort" data-val="' + cohort + '">' + cohort + ' Cohort</a></li>');
							}
						}
			});
			break;

		case 'name':
			ReHi.sendData({
				dataType: 'json',
				url: '@@@URI_ROOT@@@/do/submitted',
				type: 'post',
				success: function (response, textStatus, jqXHR) {
			  				$('#view-list').empty();
			  				var prevCohort = '', addCohort = '';
			  		 		for (var i = 0; i < response.length; i++) {
			  		 			var user = response[i];
			  		 			if(prevCohort != '' && user.cohort != prevCohort) {
			  		 				$('#view-list').append(addCohort + '</ul></li>');
			  		 				addCohort = '';
			  		 			}
			  		 			if(prevCohort == '' || user.cohort != prevCohort) {
									addCohort += '<li><a href="#cohort-' + user.cohort + '" data-toggle="collapse" data-parent="#view-list" class="collapsed">' + user.cohort + ' Cohort<span class="caret"></span></a>  <ul class="nav collapse" id="cohort-' + user.cohort + '">';
			  		 				prevCohort = user.cohort;
			  		 			}
			  		 			addCohort += '<li><a href="#" data-type="name" data-val="' + user.username + '">' + user.name + '</a></li>';
							}
			  		 		$('#view-list').append(addCohort + '</ul></li>');
						}
			});
			break;

		case 'keyword':
			$('#view-list').empty();
			$('#view-list').text('keywords');
			break;
	}
}

 $(function() {
	ReHi.fadePageIn();

	$('[data-toggle=offcanvas]').click(function() {
		$('.row-offcanvas').toggleClass('active');
	});

	$('.list-mode').click(function(e) {
		$('.list-mode').each(function(k,v) {
			if (v == e.target) {
				$(v).addClass('selected');
				showList($(v).data('listmode'))
			} else {
				$(v).removeClass('selected');
			}
		})
	});

	showList($('.list-mode.selected').data('listmode'));

});