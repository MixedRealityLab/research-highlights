
/**
 * Research Highlights engine
 *
 * Copyright (c) 2016 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

var RHAdmin = {

	FUNDING_STATEMENTS 		: [],
	DEADLINES 				: [],
	WORD_COUNTS				: [],

	register 				: function() {
								RHAdmin.fadeIn();
								RHAdmin.registerLogin();
								RHAdmin.registerEmail();
								RHAdmin.registerLogout();
							},

	fadeIn					: function() {
								var sub = 0;
								$('.collapse').each(function(i) {
									if(!$(this).hasClass('stage-admin')) {
										$(this).delay(400 * (i - sub)).fadeIn();
									} else {
										sub++;
									}
								});

								$('.navbar-toggle').addClass('stage-admin');
							},

	registerLogin			: function() {
								RH.regSubForm($('form.stage-login'), $('html').data('uri_root') + '/login.do', function(response, textStatus, jqXHR) {
									if(response.success == 1 && response.admin) {
										$('.stage-login').fadeOut({complete : function() {$('.stage-admin').fadeIn(); }});
										
										$('.submit-user').each(function() { $(this).val($('#editor').val())});
										$('.submit-pass').each(function() { $(this).val($('#password').val())});

										$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
											var fnName = $(this).attr('href').replace('#tab-', '');
											var fn = RHAdminLoadTabData[fnName];
											if(typeof fn === 'function') {
												fn();
											}
										});

										RHAdminLoadTabData.students();

										// Catch button presses
										$(window).keydown(function(e) {
											if((e.ctrlKey || e.metaKey) && e.which == 83) {
												e.preventDefault();
												$('form.stage-admin').trigger('submit');
												return false;
											}
											return true;
										});
										$('.btn-deleteRow').click(function() {
											var id = $(this).data('deletetabularrow');
											if(id != undefined) {
												$('#' + id).tabularInput("deleteRow");
											}
										});

									} else if(response.error != undefined) {
										RH.showError('Oh, snap!', response.error + ' <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									} else {
										RH.showError('Oh, snap!', 'An unknown error occurred. <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									}
								}, 'json');

								RHAdminTableInput.regSubForm('form.form-students', 'students-update', 'students');
								RHAdminTableInput.regSubForm('form.form-deadlines', 'deadlines-update', 'deadlines', 'deadline', 0);
								RHAdminTableInput.regSubForm('form.form-wordcounts', 'wordcounts-update', 'word count limits', 'wordcount', 0);
								RHAdminTableInput.regSubForm('form.form-funding', 'fundingstatements-update', 'funding statements', 'funding', 0);
								RHAdminTableInput.regSubForm('form.form-admins', 'admins-update', 'list of administrators');

							},

	registerEmail			: function() {
								RH.regSubForm($('form.form-email'), $('html').data('uri_root') + '/email.do', function(response, textStatus, jqXHR) {
									if(response.success == '1') {
										RH.showSuccess('Whoop! Whoop!', 'Your emails were sent!')
									} else if(response.error != undefined) {
										RH.showError('Goshdarnit!', response.error + ' <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									} else {
										RH.showError('Oh, snap!', 'An unknown error occurred. <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									}
								}, 'json');

								$('a[href="#tab-email"]').on('shown.bs.tab', function(e) {
									RH.autoResize('.stage-admin');
								});
							},

	registerLogout			: function() {
								$('#logout').click(function(e) {
									if(confirm('If you logout, any unsubmitted changes will be lost!')) {
										window.location.reload();
									}
								});
							},
};

var RHAdminLoadTabData= {
	students				: function() {
								RHAdminLoadTabData._users('students', 'students', 'student');
							},

	admins					: function() {
								RHAdminLoadTabData._users('admins', 'admins', 'admin');
							},

	_users					: function(ajaxFile, fieldId, inputName) {
								RH.getData($('html').data('uri_root') + '/' + ajaxFile + '.do', function(response) {
									var data = [];
									var index = 0;

									response.forEach(function(item) {
										data[index++] = item;
									});	

									jQuery('#' + fieldId).tabularInput({
										'rows': index,
										'columns': 9,
										'newRowOnTab': true,
										'maxRows': 300,
										'animate': true,
										'name': inputName,
										'columnHeads': ['Cohort', 'Username', 'First Name', 'Surname', 'Email', 'Funding Statement', 'Login Enabled', 'Show Submission', 'Notify']
									});

									RHAdminTabularValidate.users('#' + fieldId + ' tbody tr td');

									for(var i = 1; i <= index; i++) {
										var row = data[i-1];
										$('[name="' + inputName + '[0][' + i + ']"]').val(row.cohort);
										$('[name="' + inputName + '[1][' + i + ']"]').val(row.username);
										$('[name="' + inputName + '[2][' + i + ']"]').val(row.firstName);
										$('[name="' + inputName + '[3][' + i + ']"]').val(row.surname);
										$('[name="' + inputName + '[4][' + i + ']"]').val(row.email);
										$('[name="' + inputName + '[5][' + i + ']"]').val(row.fundingStatementId);
										$('[name="' + inputName + '[6][' + i + ']"]').val(row.enabled);
										$('[name="' + inputName + '[7][' + i + ']"]').val(row.countSubmission);
										$('[name="' + inputName + '[8][' + i + ']"]').val(row.emailOnChange);

										if(!row.enabled) {
											RHAdminTableInput.setCell($('[name="' + inputName + '[6][' + i + ']"]'), RHAdminTableInput.STATE_GREY_OUT);
										}
										if(!row.countSubmission) {
											RHAdminTableInput.setCell($('[name="' + inputName + '[7][' + i + ']"]'), RHAdminTableInput.STATE_STRIKETHROUGH);
										}
									}
								}, 'json');
							},

	deadlines				: function() {
								RH.getData($('html').data('uri_root') + '/deadlines.do', function(response) {
									var data = [];
									var index = 0;

									response.forEach(function(item) {
										data[index++] = item;
									});	

									jQuery('#deadlines').tabularInput({
										'rows': index,
										'columns': 2,
										'newRowOnTab': true,
										'maxRows': 15,
										'animate': true,
										'name': 'deadline',
										'columnHeads': ['Cohort', 'Deadline']
									});

									RHAdminTabularValidate.deadlines('#deadlines tbody tr td');

									for(var i = 1; i <= index; i++) {
										var row = data[i-1];
										$('[name="deadline[0][' + i + ']"]').val(row.cohort);
										$('[name="deadline[1][' + i + ']"]').val(row.deadline);
										RHAdmin.DEADLINES[i-1] = row.cohort;
									};
								}, 'json');
							},

	wordcounts				: function() {
								RH.getData($('html').data('uri_root') + '/wordcounts.do', function(response) {
									var data = [];
									var index = 0;

									response.forEach(function(item) {
										data[index++] = item;
									});	

									jQuery('#wordcounts').tabularInput({
										'rows': index,
										'columns': 2,
										'newRowOnTab': true,
										'maxRows': 15,
										'animate': true,
										'name': 'wordcount',
										'columnHeads': ['Cohort', 'Word Count']
									});

									RHAdminTabularValidate.wordcounts('#wordcounts tbody tr td');

									for(var i = 1; i <= index; i++) {
										var row = data[i-1];
										$('[name="wordcount[0][' + i + ']"]').val(row.cohort);
										$('[name="wordcount[1][' + i + ']"]').val(row.wordCount);
										RHAdmin.WORD_COUNTS[i-1] = row.cohort;
									};
								}, 'json');
							},

	funding					: function() {
								RH.getData($('html').data('uri_root') + '/fundingstatements.do', function(response) {
									var data = [];
									var index = 0;

									response.forEach(function(item) {
										data[index++] = item;
									});	

									jQuery('#funding').tabularInput({
										'rows': index,
										'columns': 2,
										'newRowOnTab': true,
										'maxRows': 15,
										'animate': true,
										'name': 'funding',
										'columnHeads': ['Unique Funding Statement ID', 'Funding Statement']
									});

									RHAdminTabularValidate.wordcounts('#funding tbody tr td');

									for(var i = 1; i <= index; i++) {
										var row = data[i-1];
										$('[name="funding[0][' + i + ']"]').val(row.fundingStatementId);
										$('[name="funding[1][' + i + ']"]').val(row.fundingStatement);
										RHAdmin.FUNDING_STATEMENTS[i-1] = row.fundingStatementId;
									};
								}, 'json');
							},

	email					: function() {
								RH.autoResize('.stage-admin');
								RH.getData($('html').data('uri_root') + '/cohorts.do', function(response) {
									var html = '';
									for(var i = 0; i < response.length; i++) {
										var cohort = response[i];
										if(html != '') {
											html += ', ';
										}
										html += '<a href="javascript:;" class="addUsers">' + cohort + '</a>';
									}
									$('#cohortLinks').html(html);

									$('.addUsers').click(function(e) {
										e.preventDefault();
										var $allInputs = $('form.form-email').find('input, select, button, textarea');

										var data = 'cohort=' + $(this).text();

										RH.sendData({
											dataType: 'json',
											data: data,
											url: $('html').data('uri_root') + '/users.do',
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
												});
												$('#usernames').trigger('autosize.resize');
											}
										});
									});
								}, 'json');
							},
};

var RHAdminTableInput = {

	STATE_OK				: 0,
	STATE_ERROR				: 1,
	STATE_GREY_OUT			: 2,
	STATE_STRIKETHROUGH		: 3,

	setCell					: function($cell, state) {
								var $row = $cell.parent().parent();

								if($row.data('countErrors') == undefined) {
									$row.data('countErrors', 0);
								}

								if(state == RHAdminTableInput.STATE_OK) {
									if($cell.hasClass('errorCell')) {
										$cell.removeClass('errorCell');
										
										if($row.data('countErrors') <= 1) {
											$row.removeClass('errorRow');
											$row.data('countErrors', 0);
										} else {
											$row.data('countErrors', $row.data('countErrors') - 1);
										}
									}

									if($cell.hasClass('disabledCell')) {
										$cell.removeClass('disabledCell');
										$row.removeClass('disabledRow');
									}

									if($cell.hasClass('strikedCell')) {
										$cell.removeClass('strikedCell');
										$row.removeClass('strikedRow');
									}
								} else if(state == RHAdminTableInput.STATE_GREY_OUT) {
									$cell.addClass('disabledCell');
									$row.addClass('disabledRow');

									if($cell.hasClass('errorCell')) {
										$cell.removeClass('errorCell');
										
										if($row.data('countErrors') == 1) {
											$row.removeClass('errorRow');
											$row.data('countErrors', 0);
										} else {
											$row.data('countErrors', $row.data('countErrors') - 1);
										}
									}
								} else if(state == RHAdminTableInput.STATE_STRIKETHROUGH) {
									$cell.addClass('strikedCell');
									$row.addClass('strikedRow');

									if($cell.hasClass('errorCell')) {
										$cell.removeClass('errorCell');
										
										if($row.data('countErrors') == 1) {
											$row.removeClass('errorRow');
											$row.data('countErrors', 0);
										} else {
											$row.data('countErrors', $row.data('countErrors') - 1);
										}
									}
								} else {
									$row.data('countErrors', $row.data('countErrors') + 1);
									$cell.addClass('errorCell');
									$row.addClass('errorRow');
								}
							},

	clearErrorsOnRow		: function($row) {
								if($row.data('countErrors') == undefined) {
									$row.data('countErrors', 0);
								}

								$row.removeClass('errorRow');
								$row.find('input').removeClass('errorCell');
								$row.data('countErrors', 0);
							},

	validateCell			: function($cell, okValues, greyOutValues, strikethroughValues) {
								if(RHAdminTableInput.rowIsEmpty($cell)) {
									RHAdminTableInput.clearErrorsOnRow($cell);
									return;
								}

								var stateSet = false;
								if(okValues != undefined && $.inArray($cell.val(), okValues) >= 0) {
									RHAdminTableInput.setCell($cell, RHAdminTableInput.STATE_OK);
									stateSet = true;
								}
								if(greyOutValues != undefined && $.inArray($cell.val(), greyOutValues) >= 0) {
									RHAdminTableInput.setCell($cell, RHAdminTableInput.STATE_GREY_OUT);
									stateSet = true;
								}
								if(strikethroughValues != undefined && $.inArray($cell.val(), strikethroughValues) >= 0) {
									RHAdminTableInput.setCell($cell, RHAdminTableInput.STATE_STRIKETHROUGH);
									stateSet = true;
								}
								if(!stateSet) {
									RHAdminTableInput.setCell($cell, RHAdminTableInput.STATE_ERROR);
								}
							},

	validateCellFn			: function($cell, validateFn) {
								var result = validateFn($cell.val());
								if(result == 0) { // set cell to positive
									if(RHAdminTableInput.rowIsErrorFree($cell)) {
										RHAdminTableInput.clearErrorsOnRow($cell.parent().parent());
									} else {
										RHAdminTableInput.setCell($cell, RHAdminTableInput.STATE_OK);
									}
								} else if(result == 1) { // set row to positive
									RHAdminTableInput.clearErrorsOnRow($cell.parent().parent());
								} else { // fail on this crll
									RHAdminTableInput.setCell($cell, RHAdminTableInput.STATE_ERROR);
								}
							},

	rowIsErrorFree			: function($cell) {
								var $siblings = $cell.parent().parent().find('input');
								var $errorFreeSiblings = $siblings.filter(function() {
									return !$(this).hasClass('errorCell');
								});

								return $errorFreeSiblings.length > 0 && $siblings.length == $errorFreeSiblings.length;
							},

	rowIsEmpty				: function($cell) {
								var $siblings = $cell.parent().parent().find('input');
								var $emptySiblings = $siblings.filter(function() {
									return $.trim(this.value) === "";
								});

								return $emptySiblings.length > 0 && $siblings.length == $emptySiblings.length;
							},

	regSubForm				: function(formClass, ajaxFile, label, cellName, cellIndexCol) {
								RH.regSubForm($(formClass), $('html').data('uri_root') + '/' + ajaxFile + '.do', function(response, textStatus, jqXHR) {
									if(response.success == 1) {
										if(cellName != undefined && cellIndexCol != undefined) {
											var i = 1;
											while(i < 25) {
												var $cell = $(formClass + ' input[name="' + cellName + '[' + cellIndexCol + '][' + i + ']"]');
												if($cell.length == 1) {
													RHAdmin.DEADLINES[i-1] = $cell.val();
												} else {
													break;
												}
												i++;
											}
										}
										
										RH.showSuccess('Configuration Saved', 'The ' + label + ' were updated.');
									} else if(response.error != undefined) {
										RH.showError('Oh, snap!', response.error + ' <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									} else {
										RH.showError('Oh, snap!', 'An unknown error occurred. <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									}
								}, 'json', null, function() {
									if($(formClass + ' input.errorCell').length > 0) {
										$(formClass + ' input.errorCell').focus();
										return false;
									}
									return true;
								});
							},
};

var RHAdminTabularValidate = {
	revalidate				: function(rowSelector, lastColIndex, validateFn) {
								$(rowSelector + ':nth-child(' + lastColIndex + ') input').on('keydown', function(e) {
									if(e.which === 9 && $(e.target).closest('tr').is(':last-child')) {
										$(rowSelector + ' input').unbind('change');
										setTimeout(function() { validateFn(rowSelector); }, 500);
									}
								});
							},

	users 					: function(rowSelector) {
								$(rowSelector + ':nth-child(1) input').change(function() {
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : ((Math.floor(val) == val && $.isNumeric(val) && $.inArray(val, RHAdmin.DEADLINES) > -1 && $.inArray(val, RHAdmin.WORD_COUNTS) > -1) ? 0 : -1);
									}); 
								});

								$(rowSelector + ':nth-child(n+2):nth-child(-n+5) input').change(function() {
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : (val.length > 0 ? 0 : -1);
									});
								});

								$(rowSelector + ':nth-child(6) input').change(function() {
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : ((val.length > 0 && $.inArray(val, RHAdmin.FUNDING_STATEMENTS) > -1) ? 0 : -1);
									});
								});

								$(rowSelector + ':nth-child(7) input').change(function() {
									RHAdminTableInput.validateCell($(this), ['true'], ['false']);
								});

								$(rowSelector + ':nth-child(8) input').change(function() {
									RHAdminTableInput.validateCell($(this), ['true'], undefined, ['false']);
								});

								$(rowSelector + ':nth-child(9) input').change(function() {
									RHAdminTableInput.validateCell($(this), ['true', 'false']);
								});

								RHAdminTabularValidate.revalidate(rowSelector, 9, RHAdminTabularValidate.users);
							},

	deadlines 				: function(rowSelector) {
								$(rowSelector + ':nth-child(1) input').change(function() {
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : ((val.length > 0 && Math.floor(val) == val && $.isNumeric(val)) ? 0 : -1);
									});
								});

								$(rowSelector + ':nth-child(2) input').change(function() {
									console.log('cell changed');
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : (val.length > 0 ? 0 : -1);
									});
								});

								RHAdminTabularValidate.revalidate(rowSelector, 2, RHAdminTabularValidate.deadlines);
							},

	wordcounts 				: function(rowSelector) {
								$('#wordcounts tbody tr td:nth-child(1) input').change(function() {
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : ((val.length > 0 && Math.floor(val) == val && $.isNumeric(val)) ? 0 : -1);
									});
								});

								$('#wordcounts tbody tr td:nth-child(2) input').change(function() {
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : ((val.length > 0 && Math.floor(val) == val && $.isNumeric(val)) ? 0 : -1);
									});
								});

								RHAdminTabularValidate.revalidate(rowSelector, 2, RHAdminTabularValidate.wordcounts);
							},

	fundingStatements 		: function(rowSelector) {
								$(rowSelector + ' input').change(function() {
									var input = this;

									RHAdminTableInput.validateCellFn($(this), function(val) {
										return RHAdminTableInput.rowIsEmpty($(input)) ? 1 : (val.length > 0 ? 0 : -1);
									});
								});

								RHAdminTabularValidate.revalidate(rowSelector, 2, RHAdminTabularValidate.fundingStatements);
							},
};

$(RHAdmin.register);
