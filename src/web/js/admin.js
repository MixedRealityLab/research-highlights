
/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 $(function () {
	ReHi.regSubForm ($('#profile-form'), '@@@URI_ROOT@@@/do/login', function (response, textStatus, jqXHR) {
		// if (changesMade && !confirm ('Are you sure you want to change user?\nAny unsubmitted changes will be lost.')) {
		// 	return false;
		// }

		if (response.success !== undefined) {
			$('#saveAs').attr ('value', $('#profile').val ());
			loginPrefill (response, textStatus, jqXHR);
			ReHi.showSuccess ('Loaded submission from ' + response.firstName + ' ' + response.surname, 'Please make any changes necessary and click the Submit button.');
		} else if (response.error !== undefined) {
			ReHi.showError ('User Error', response.error);
		} else {
			ReHi.showError ('User Error', 'An unknown error occured!');
		}
	}, 'json');

	// $('#profile').keyup (function (e){
	// 	if (e.keyCode == 13) {
	// 		//$('#profile-form').submit ();
	// 	}
	// });

	$('#profile-form').removeClass ('hidden');
});
