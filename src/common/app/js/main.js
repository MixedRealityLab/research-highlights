
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 var ReHi = {
	urlPrefix			: 'index.php/',
	
	fadePageIn			: function() {
							$('.collapse').each(function(i) {
								$(this).delay(200 * i).fadeIn();
							});
						},

	fadePageOut			: function(complete) {
							$.fn.reverse = [].reverse;
							var c = $('.collapse').length - 1; 
							$('.collapse').reverse().each(function(i, v) {
								$(v).delay(200 * i).fadeOut();
								if (i == c) {
									setTimeout(complete, 400 * (i+1));
								}
							});
						},

	charCount			: function($field, $output, limit) {
							var len = $field.val().length;
							if($field.val().trim().length == 0) {
								$output.text(limit);
								return;
							}
							
							if (len > limit) {
								$field.val($field.val().substring(0, limit));
								$output.text(0);
							} else {
								$output.text(limit - len);
							}
						},

	wordCount			: function($field, $output, limit) {
							var txt = $field.val().trim().split(/\s+/);
							var len = 0;
							if($field.val().trim().length == 0) {
								$output.text(limit);
								return;
							}

							$.each(txt, function(k, v) {
								var wordLen = v.length;
								if(v[0] == '&' && v[wordLen-1] == ';') {
									return;
								}

								if(v.match(/#+$/) || v.match(/\*+$/) || v.match(/>$/) || v.match(/==+/) || v.match(/\++/) || v.match(/-+$/) || v.match(/[0-9]+[.]$/) || v.match(/\[[0-9, ]+\]/) || v.match(/[0-9, ]+\]/) || v.match(/\[[0-9, ]+/)) {
									return;
								}

								len++;
							});

							if (len > limit) {
								$field.val($field.val().substring(0, limit));
								$output.text(0);
							} else {
								$output.text(limit - len);
							}
						},

	rqst				: 0,

	sendData			: function(data) {
							if (ReHi.rqst) {
								ReHi.rqst.abort();
							}
							ReHi.rqst = $.ajax(data);
						},

	showAlert2			: function (title, mesg, className) {
							$('.container.main').before($('<div class="alert alert-' + className + ' alert-dismissable collapse" role="alert-dismissable"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><div class="container"><div class="row"><h4>' + title + '</h4>' + mesg + '</div></div></div>'));
							$('.alert-dismissable').fadeIn();
						},

	showAlert			: function (title, mesg, className) {
							if ($('.alert-dismissable').length == 0) {
								ReHi.showAlert2(title, mesg, className);
							} else {
								$('.alert-dismissable').fadeOut({complete: function() {$('.alert-dismissable').remove(); ReHi.showAlert2(title, mesg, className);}});
							}
							$('html, body').animate({ scrollTop: 0 }, 600);
						},

	showError			: function (title, mesg) {
							ReHi.showAlert(title, mesg, 'danger');
						},

	showSuccess			: function (title, mesg) {
							ReHi.showAlert(title, mesg, 'success');
						},

	regAutoForm			: function($form, url, handler) {
							$form.find('input, textarea, select').change(function(e) {
								ReHi.sendData({
									url: url,
									type: "post",
									data: $form.serialize(),
									success: handler
								});
								return true;
							});
							$form.find('input, textarea').keyup(function(e) {
								ReHi.sendData({
									url: url,
									type: "post",
									data: $form.serialize(),
									success: handler
								});
								return true;
							});
						},

	regSubForm			: function($form, url, success, dataType) {
							$form.submit(function(e) {
								e.preventDefault();
								ReHi.submitForm($form, url, success, dataType);
							});
						},

	submitForm			: function($form, url, successFn, dataType) {
							var $allInputs = $form.find('input, select, button, textarea');
							var data = $form.serialize(); 
							
							ReHi.sendData({
								dataType: (dataType == undefined ? 'text' : dataType),
								url: url,
								type: 'post',
								beforeSend: function() {
									$allInputs.prop('disabled', true);
								},
								complete: function() {
									$allInputs.prop('disabled', false);
								},
								data: data,
								success: successFn
							});
						}
};