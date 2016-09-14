
/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 $(function () {
	ReHi.regSubForm ($('#profile-form'), $('html').data('uri_root') + '/login.do', function (response, textStatus, jqXHR) {
		if (response.success !== undefined) {
			$('#username').attr ('value', $('#profile').val ());
			loginPrefill (response, textStatus, jqXHR);
			ReHi.showSuccess ('Loaded submission from ' + response.firstName + ' ' + response.surname, 'Please make any changes necessary and click the Submit button.');
            changesMade = false;
		} else if (response.error !== undefined) {
			ReHi.showError ('User Error', response.error);
		} else {
			ReHi.showError ('User Error', 'An unknown error occured!');
		}
	}, 'json', function () {
        // console.log ('ffff');
        // if (changesMade && confirm ('Do you want to submit the changes made?')) {
        //     $('form.stage-editor').triggerHandler ('submit');
        // }
        return true;
    });

	$('#stage-login').removeClass ('collapse');
	$('#profile-form').removeClass ('hidden');
});
