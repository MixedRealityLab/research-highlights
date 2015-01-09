var wordCount = 1500;
var year = 1;

$(function() {
	var sub = 0;
	$('.collapse').each(function(i) {
		if(!$(this).hasClass('stage-email')) {
			$(this).delay(400 * (i - sub)).fadeIn();
		} else {
			sub++;
		}
	});

	ReHi.showAlert('Welcome!', 'Please enter your credentials to continue.', 'info'); 

	ReHi.regSubForm($('form.stage-login'), ReHi.urlPrefix + 'do/login', function (response, textStatus, jqXHR) {
		if (response == '-3') {
			ReHi.showError('Humph!', 'Your account has been disabled. <a href="mailto:cdt-rh@lists.porcheron.uk" class="alert-link">I need help!</a>');
		} else if (response == '-1') {
			ReHi.showError('Woops!', 'Looks like you\'ve entered an invalid username/password combination. <a href="mailto:cdt-rh@lists.porcheron.uk" class="alert-link">I need help!</a>'); 
		} else if (response.success == '1') {
			ReHi.showSuccess('Welcome!', 'Your login was successful.');

			$('#submit-user').attr('value', $('#username').val());
			$('#submit-pass').attr('value', $('#password').val());
			
			$('.stage-login').fadeOut({complete : function() {$('.stage-email').fadeIn(); $('.stage-email textarea').trigger('autosize.resize');	}});
		}
	}, 'json');

	ReHi.regSubForm($('form.stage-email'), ReHi.urlPrefix + 'do/email', function (response, textStatus, jqXHR) {
		if (response != '1') {
			ReHi.showError('Goshdarnit!', 'Something has gone wrong! (error: ' + response + ')');
		} else {
			ReHi.showSuccess('Whoop! Whoop!', 'Your emails were sent!')
		}
	});	

	$('.addUsers').click(function(e) {
		e.preventDefault();
		var $allInputs = $('form.stage-email').find('input, select, button, textarea');
		var cohort = $(this).text();
		ReHi.sendData({
			dataType: 'json',
			data: 'cohort=' + cohort,
			url: ReHi.urlPrefix + 'do/users',
			type: 'post',
			beforeSend: function() {
				$allInputs.prop('disabled', true);
			},
			complete: function() {
				$allInputs.prop('disabled', false);
			},
			success: function(response, textStatus, jqXHR) {
				$.each(response, function(i, v) {
					$('#usernames').append(v.username + "\n");
					$('#usernames').trigger('autosize.resize'); 
				});
			}
		});
		});

	$('#submit').click(function(e) {
		e.preventDefault();
		$('form.stage-email').triggerHandler('submit');
	});

	$('#logout').click(function(e) {
		if (confirm('If you logout, any unsubmitted changes will be lost')) {
			window.location.reload();
		}
	});

	$('textarea').autosize(); 
});