
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 $(function() {
	ReHi.regSubForm($('#profile-form'), '@@@URI_ROOT@@@/do/login', function(response, textStatus, jqXHR) {
		if (changesMade && !confirm('Are you sure you want to change user?\nAny unsubmitted changes will be lost.')) {
			return false;
		}

		if (response == '-1') {
			ReHi.showError('Unknown Error', 'You don\'t seem to be logged in.');
		} else if (response == '-4') {
			ReHi.showError('User Error', 'Could not find a valid user with the username <em>' + $('#profile').val()  + '</em> - did you enter the correct username?');
		} else {

			$('#saveAs').attr('value', $('#profile').val());
			loginPrefill(response, textStatus, jqXHR);
			ReHi.showSuccess('Loaded submission from ' + response.firstName + ' ' + response.surname, 'Please make any changes necessary and click the Submit button.');
		}
	}, 'json');

	// $('#profile').keyup(function(e){
	// 	if(e.keyCode == 13) {
	// 		//$('#profile-form').submit();
	// 	}
	// });

	$('#profile-form').removeClass('hidden');
});