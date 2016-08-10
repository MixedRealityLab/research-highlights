
/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$(function () {
	ReHi.fadePageIn();

	ReHi.regSubForm ($('form.stage-forgotten'), $('html').data('uri_root') + '/forgotten.do', function (response, textStatus, jqXHR) {
		if (response.success != undefined) {
			ReHi.showSuccess ('Success!', 'Your password has been sent to your email address.');
			setTimeout(function() {window.location=$('html').data('uri_root') + '/login'}, 2500);
		} else if (response.error != undefined) {
			ReHi.showError ('Humph!', response.error + ' <a href="mailto:' + $('html').data('email') + '" class="alert-link">I need help!</a>');
		} else {
			ReHi.showError ('Woops!', 'An unknown error occured. <a href="mailto:' + $('html').data('email') + '" class="alert-link">I need help!</a>');
		}
	}, 'json');

	$('#submit').click (function (e) {
		e.preventDefault ();
		$('form.stage-forgotten').triggerHandler ('submit');
	});
});
