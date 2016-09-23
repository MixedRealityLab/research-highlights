
/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 var RH = {
 	// from http://stackoverflow.com/questions/1144783/replacing-all-occurrences-of-a-string-in-javascript
 	replaceAll			: function(find, replace, str) {
							return str.replace (new RegExp (find, 'g'), replace);
						},

	fadePageIn			: function () {
							$('.collapse').each (function (i) {
								if ($(this).hasClass ('noAutoFadeIn')) {
									return;
								} else {
									$(this).delay (200 * i).fadeIn ();
								}
							});
						},

	fadePageOut			: function (complete) {
							$.fn.reverse = [].reverse;
							var c = $('.collapse').length - 1;
							$('.collapse').reverse ().each (function (i, v) {
								$(v).delay (200 * i).fadeOut ();
								if (i == c) {
									setTimeout (complete, 400 * (i+1));
								}
							});
						},

	charCount			: function ($field, $output, limit) {
							var len = $field.val ().length;
							if ($field.val ().trim ().length == 0) {
								$output.text (limit);
								return;
							}

							if (len > limit) {
								$field.val ($field.val ().substring (0, limit));
								$output.text (0);
							} else {
								$output.text (limit - len);
							}
						},

	wordCount			: function ($field, $output, limit) {
							var txt = $field.val ().trim ().split (/\s+/);
							var len = 0;
							if ($field.val ().trim ().length == 0) {
								$output.text (limit);
								return;
							}

							$.each (txt, function (k, v) {
								var wordLen = v.length;
								if (v[0] == '&' && v[wordLen-1] == ';') {
									return;
								}

								if (v.match (/#+$/) || v.match (/\*+$/) || v.match (/>$/) || v.match (/==+/) || v.match (/\++/) || v.match (/-+$/) || v.match (/[0-9]+[.]$/) || v.match (/\[[0-9, ]+\]/) || v.match (/[0-9, ]+\]/) || v.match (/\[[0-9, ]+/)) {
									return;
								}

								len++;
							});

							//if (len > limit) {
							//	$field.val ($field.val ().substring (0, limit));
								$output.text (0);
							//} else {
								$output.text (limit - len);
							//}
						},

	activeRequest		: 0,

	requestQueue		: [],

	sendData			: function (data) {
							if (RH.activeRequest == 0) {
								RH.activeRequest = $.ajax(data).always(function() {
									RH.activeRequest = 0;
									if(RH.requestQueue[0] != undefined) {
										RH.sendData(RH.requestQueue.shift());
									}
								});
							} else {
								RH.requestQueue.push(data);
							}
						},

	showAlert2			: function (title, mesg, className) {
							$('.container.main').before ($('<div class="alert alert-' + className + ' alert-dismissable collapse" role="alert-dismissable"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><div class="container"><div class="row"><h4>' + title + '</h4>' + mesg + '</div></div></div>'));
							$('.alert-dismissable').fadeIn ();
						},

	showAlert			: function (title, mesg, className) {
							if ($('.alert-dismissable').length == 0) {
								RH.showAlert2 (title, mesg, className);
							} else {
								$('.alert-dismissable').fadeOut ({complete: function () {$('.alert-dismissable').remove (); RH.showAlert2 (title, mesg, className);}});
							}
							$('html, body').animate ({ scrollTop: 0 }, 600);
						},

	showError			: function (title, mesg) {
							RH.showAlert (title, mesg, 'danger');
						},

	showSuccess			: function (title, mesg) {
							RH.showAlert (title, mesg, 'success');
						},

	regAutoForm			: function ($form, url, handler) {
							$form.find ('input, textarea, select').change (function (e) {
								RH.sendData ({
									url: url,
									type: "post",
									data: $form.serialize (),
									success: handler
								});
								return true;
							});
							$form.find ('input, textarea').keyup (function (e) {
								RH.sendData ({
									url: url,
									type: "post",
									data: $form.serialize (),
									success: handler
								});
								return true;
							});
						},

	regSubForm			: function ($form, url, success, dataType, confirmationFn, validateFn) {
                            if (confirmationFn == undefined) {
                                confirmationFn = function () { return true; }
                            }
							$form.submit (function (e) {
								e.preventDefault ();
								if(validateFn == undefined || validateFn($form)) {
									RH.submitForm ($form, url, success, dataType, confirmationFn);
								}
							});
						},

	submitForm			: function ($form, url, successFn, dataType, confirmationFn) {
							var $allInputs = $form.find ('input, select, button, textarea');
							var data = $form.serialize ();

                            if (confirmationFn () == false) {
                                return false;
                            }

							RH.sendData ({
								dataType: (dataType == undefined ? 'text' : dataType),
								url: url,
								type: 'post',
								beforeSend: function () {
									$allInputs.prop ('disabled', true);
								},
								always: function () {
									$allInputs.prop ('disabled', false);
								},
								data: data,
								success: successFn
							});
						},

	getData				: function (url, successFn, dataType) {
							$.ajax ({
								dataType: (dataType == undefined ? 'text' : dataType),
								url: url,
								type: 'post',
								success: successFn
							});
						}
};

// from http://stackoverflow.com/questions/946534/insert-text-into-textarea-with-jquery
jQuery.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      //For browsers like Internet Explorer
      this.focus();
      var sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      //For browsers like Firefox and Webkit based
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  });
}
});
