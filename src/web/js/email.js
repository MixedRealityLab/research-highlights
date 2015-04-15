
/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

var wordCount = 1500;
var year = 1;

$(function () {
	var sub = 0;
	$('.collapse').each (function (i) {
		if (!$(this).hasClass ('stage-email')) {
			$(this).delay (400 * (i - sub)).fadeIn ();
		} else {
			sub++;
		}
	});

	ReHi.showAlert ('Welcome!', 'Please enter your credentials to continue.', 'info');

	ReHi.regSubForm ($('form.stage-login'), '@@@URI_ROOT@@@/login.do', function (response, textStatus, jqXHR) {
		if (response.success != undefined) {
			ReHi.showSuccess ('Welcome!', 'Your login was successful.');

			$('#submit-user').attr ('value', $('#username').val ());
			$('#submit-pass').attr ('value', $('#password').val ());

			$('.stage-login').fadeOut ({complete : function () {$('.stage-email').fadeIn (); $('.stage-email textarea').trigger ('autosize.resize');	}});
		} else if (response.error != undefined) {
			ReHi.showError ('Oh, snap!', response.error + ' <a href="mailto:@@@EMAIL@@@" class="alert-link">Email support</a> for help.');
		} else {
			ReHi.showError ('Oh, snap!', 'An unknown error occurred. <a href="mailto:@@@EMAIL@@@" class="alert-link">Email support</a> for help.');
	}
	}, 'json');

	ReHi.regSubForm ($('form.stage-email'), '@@@URI_ROOT@@@/email.do', function (response, textStatus, jqXHR) {
		if (response != '1') {
			ReHi.showError ('Goshdarnit!', 'Something has gone wrong! (error: ' + response + ')');
		} else {
			ReHi.showSuccess ('Whoop! Whoop!', 'Your emails were sent!')
		}
	});


	ReHi.sendData ({
		dataType: 'json',
		url: '@@@URI_ROOT@@@/cohorts.do',
		type: 'post',
		success: function (response, textStatus, jqXHR) {
					var html = '';
					for (var i = 0; i < response.length; i++) {
						var cohort = response[i];
						if (html != '') {
							html += ', ';
						}
						html += '<a href="#" class="addUsers">' + cohort + '</a>';
					}
					$('#cohortLinks').html (html);

					$('.addUsers').click (function (e) {
						e.preventDefault ();
						var $allInputs = $('form.stage-email').find ('input, select, button, textarea');

						var data = '';
						var input = $(this).text ();
						if (input == 'submitted') {
							data = 'submitted=1';
						} else if (input == 'not submitted') {
							data = 'submitted=0';
						} else {
							data = 'cohort=' + input;
						}

						ReHi.sendData ({
							dataType: 'json',
							data: data,
							url: '@@@URI_ROOT@@@/users.do',
							type: 'post',
							beforeSend: function () {
								$allInputs.prop ('disabled', true);
							},
							complete: function () {
								$allInputs.prop ('disabled', false);
							},
							success: function (response, textStatus, jqXHR) {
								$.each (response, function (i, v) {
									$('#usernames').append (v.username + "\n");
								});
								$('#usernames').trigger('autosize.resize');
							}
						});
					});
				}
	});

	$('#submit').click (function (e) {
		e.preventDefault ();
		$('form.stage-email').triggerHandler ('submit');
	});

	$('#logout').click (function (e) {
		if (confirm ('If you logout, any unsubmitted changes will be lost')) {
			window.location.reload ();
		}
	});

	$('textarea').autosize ();
});
