
/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

function autoResize () {
	$('.stage-admin textarea').each (function () {$(this).trigger ('autosize.resize'); });
}

var adminTabular = {
	STATE_OK				: 0,
	STATE_ERROR				: 1,
	STATE_DISABLED			: 2,

	setCell					: function($cell, state) {
								var $row = $cell.parent().parent();

								if($row.data('countErrors') == undefined) {
									$row.data('countErrors', 0);
								}

								if(state == adminTabular.STATE_OK) {
									if($cell.hasClass('errorCell')) {
										$cell.removeClass('errorCell');
										
										if($row.data('countErrors') <= 1) {
											$row.removeClass('errorRow');
											$row.data('countErrors', 0);
										} else {
											$row.data('countErrors', $row.data('countErrors') - 1);
										}
									}

									if ($cell.hasClass('disabledCell')) {
										$cell.removeClass('disabledCell');
										$row.removeClass('disabledRow');
									}
								} else if(state == adminTabular.STATE_DISABLED) {
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
								} else {
									$row.data('countErrors', $row.data('countErrors') + 1);
									$cell.addClass('errorCell');
									$row.addClass('errorRow');
								}
							},

	enableRow				: function($row) {
								if($row.data('countErrors') == undefined) {
									$row.data('countErrors', 0);
								}

								$row.removeClass('errorRow');
								$row.find('input').removeClass('errorCell');
								$row.data('countErrors', 0);
							},

	validateCell			: function($cell, posValid, negValid) {
								if(adminTabular.rowIsEmpty($cell.parent().parent())) {
									adminTabular.enableRow($cell);
									return;
								}

								if($.inArray($cell.val(), posValid) >= 0) {
									adminTabular.setCell($cell, adminTabular.STATE_OK);
								} else if($.inArray($cell.val(), negValid) >= 0) {
									adminTabular.setCell($cell, adminTabular.STATE_DISABLED);
								} else {
									adminTabular.setCell($cell, adminTabular.STATE_ERROR);
								}
							},

	validateCellFn			: function($cell, validateFn) {
								var result = validateFn($cell.val());
								if(result == 0) { // set cell to positive
									adminTabular.setCell($cell, adminTabular.STATE_OK);
								} else if(result == 1) { // set row to positive
									adminTabular.enableRow($cell.parent().parent());
								} else { // fail on this crll
									adminTabular.setCell($cell, adminTabular.STATE_ERROR);
								}
							},

	rowIsEmpty				: function(input) {
								var $siblings = $(input).parent().parent().find('input');
								var $emptySiblings = $siblings.filter(function() {
									return $.trim(this.value) === "";
								});

								return $emptySiblings.length > 0 && $siblings.length == $emptySiblings.length;
							},

	regSubForm				: function(formClass, ajaxFile, label, cellName, cellIndexCol) {
								ReHi.regSubForm ($(formClass), $('html').data('uri_root') + '/' + ajaxFile + '.do', function (response, textStatus, jqXHR) {
									if (response.success == 1) {
										if(cellName != undefined && cellIndexCol != undefined) {
											var i = 1;
											while(i < 25) {
												var $cell = $(formClass + ' input[name="' + cellName + '[' + cellIndexCol + '][' + i + ']"]');
												if ($cell.length == 1) {
													adminData.deadlines[i-1] = $cell.val();
												} else {
													break;
												}
												i++;
											}
										}
										
										ReHi.showSuccess ('Configuration Saved', 'The ' + label + ' were updated.');
									} else if (response.error != undefined) {
										ReHi.showError ('Oh, snap!', response.error + ' <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									} else {
										ReHi.showError ('Oh, snap!', 'An unknown error occurred. <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
									}
								}, 'json', null, function() {
									if ($(formClass + ' input.errorCell').length > 0) {
										$(formClass + ' input.errorCell').focus();
										return false;
									}
									return true;
								});
								}
};

var adminValidate = {
	revalidate					: function(rowSelector, lastColIndex, validateFn) {
									$(rowSelector + ':nth-child(' + lastColIndex + ') input').on('keydown', function (e) {
										if (e.which === 9 && $(e.target).closest('tr').is(':last-child')) {
											$(rowSelector + ' input').unbind('change');
											setTimeout(function() { validateFn(rowSelector); }, 500);
										}
									});
								},

	users 						: function(rowSelector) {
									$(rowSelector + ':nth-child(1) input').change(function() {
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : ((Math.floor(val) == val && $.isNumeric(val) && $.inArray(val, adminData.deadlines) > -1 && $.inArray(val, adminData.wordCounts) > -1) ? 0 : -1);
										}); 
									});

									$(rowSelector + ':nth-child(n+2):nth-child(-n+5) input').change(function() {
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : (val.length > 0 ? 0 : -1);
										});
									});

									$(rowSelector + ':nth-child(6) input').change(function() {
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : ((val.length > 0 && $.inArray(val, adminData.fundingStatements) > -1) ? 0 : -1);
										});
									});

									$(rowSelector + ':nth-child(7) input').change(function() {
										adminTabular.validateCell($(this), ['true'], ['false']);
									});

									$(rowSelector + ':nth-child(8) input').change(function() {
										adminTabular.validateCell($(this), ['true', 'false'], []);
									});

									$(rowSelector + ':nth-child(9) input').change(function() {
										adminTabular.validateCell($(this), ['true', 'false'], []);
									});

									adminValidate.revalidate(rowSelector, 9, adminValidate.users);
								},

	deadlines 					: function(rowSelector) {
									$(rowSelector + ':nth-child(1) input').change(function() {
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : ((val.length > 0 && Math.floor(val) == val && $.isNumeric(val)) ? 0 : -1);
										});
									});

									$(rowSelector + ':nth-child(2) input').change(function() {
										console.log('cell changed');
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : (val.length > 0 ? 0 : -1);
										});
									});

									adminValidate.revalidate(rowSelector, 2, adminValidate.deadlines);
								},

	wordcounts 					: function(rowSelector) {
									$('#wordcounts tbody tr td:nth-child(1) input').change(function() {
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : ((val.length > 0 && Math.floor(val) == val && $.isNumeric(val)) ? 0 : -1);
										});
									});

									$('#wordcounts tbody tr td:nth-child(2) input').change(function() {
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : ((val.length > 0 && Math.floor(val) == val && $.isNumeric(val)) ? 0 : -1);
										});
									});

									adminValidate.revalidate(rowSelector, 2, adminValidate.wordcounts);
								},

	fundingStatements 			: function(rowSelector) {
									$(rowSelector + ' input').change(function() {
										var input = this;

										adminTabular.validateCellFn($(this), function(val) {
											return adminTabular.rowIsEmpty(input) ? 1 : (val.length > 0 ? 0 : -1);
										});
									});

									adminValidate.revalidate(rowSelector, 2, adminValidate.fundingStatements);
								},
};

var adminData = {

	fundingStatements 			: [],
	deadlines 					: [],
	wordCounts 					: [] 

};

var loadUserTable = function(ajaxFile, fieldId, inputName) {
	ReHi.getData($('html').data('uri_root') + '/' + ajaxFile + '.do', function(response) {
		var data = [];
		var index = 0;

		response.forEach(function (item) {
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

		adminValidate.users('#' + fieldId + ' tbody tr td');

		for (var i = 1; i <= index; i++) {
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
				adminTabular.setCell($('[name="' + inputName + '[6][' + i + ']"]'), adminTabular.STATE_DISABLED);
			}
		}
	}, 'json');
};

$(function () {

	var sub = 0;
	$('.collapse').each (function (i) {
		if (!$(this).hasClass ('stage-admin')) {
			$(this).delay (400 * (i - sub)).fadeIn ();
		} else {
			sub++;
		}
	});

	$('.navbar-toggle').addClass ('stage-admin');

	ReHi.regSubForm ($('form.stage-login'), $('html').data('uri_root') + '/login.do', function (response, textStatus, jqXHR) {
		if (response.success == 1 && response.admin) {
			$('.stage-login').fadeOut ({complete : function () {$('.stage-admin').fadeIn (); }});
			
			$('.submit-user').each(function() { $(this).val ($('#editor').val ())});
			$('.submit-pass').each(function() { $(this).val ($('#password').val ())});

			// Load Students
			loadUserTable('students', 'students', 'student');

			// Load Deadlines
			ReHi.getData($('html').data('uri_root') + '/deadlines.do', function(response) {
				var data = [];
				var index = 0;

				response.forEach(function (item) {
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

				adminValidate.deadlines('#deadlines tbody tr td');

				for (var i = 1; i <= index; i++) {
					var row = data[i-1];
					$('[name="deadline[0][' + i + ']"]').val(row.cohort);
					$('[name="deadline[1][' + i + ']"]').val(row.deadline);
					adminData.deadlines[i-1] = row.cohort;
				};
			}, 'json');

			// Load Word Counts
			ReHi.getData($('html').data('uri_root') + '/wordcounts.do', function(response) {
				var data = [];
				var index = 0;

				response.forEach(function (item) {
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

				adminValidate.wordcounts('#wordcounts tbody tr td');

				for (var i = 1; i <= index; i++) {
					var row = data[i-1];
					$('[name="wordcount[0][' + i + ']"]').val(row.cohort);
					$('[name="wordcount[1][' + i + ']"]').val(row.wordCount);
					adminData.wordCounts[i-1] = row.cohort;
				};
			}, 'json');

			// Load Funding Statements
			ReHi.getData($('html').data('uri_root') + '/fundingstatements.do', function(response) {
				var data = [];
				var index = 0;

				response.forEach(function (item) {
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

				adminValidate.wordcounts('#funding tbody tr td');

				for (var i = 1; i <= index; i++) {
					var row = data[i-1];
					$('[name="funding[0][' + i + ']"]').val(row.fundingStatementId);
					$('[name="funding[1][' + i + ']"]').val(row.fundingStatement);
					adminData.fundingStatements[i-1] = row.fundingStatementId;
				};
			}, 'json');

			// Load Administrators
			loadUserTable('admins', 'admins', 'admin');

			// Catch Key presses for submitting
			$(window).keydown (function (e) {
				if ((e.ctrlKey || e.metaKey) && e.which == 83) {
					e.preventDefault();
					$('form.stage-admin').trigger('submit');
					return false;
				}
				return true;
			});

			// Load Cohorts
			autoResize();
			ReHi.getData($('html').data('uri_root') + '/cohorts.do', function(response) {
				var html = '';
				for (var i = 0; i < response.length; i++) {
					var cohort = response[i];
					if (html != '') {
						html += ', ';
					}
					html += '<a href="javascript:;" class="addUsers">' + cohort + '</a>';
				}
				$('#cohortLinks').html (html);

				$('.addUsers').click (function (e) {
					e.preventDefault ();
					var $allInputs = $('form.form-email').find ('input, select, button, textarea');

					var data = 'cohort=' + $(this).text();

					ReHi.sendData ({
						dataType: 'json',
						data: data,
						url: $('html').data('uri_root') + '/users.do',
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
			}, 'json');

			$('.btn-deleteRow').click(function() {
				var id = $(this).data('deletetabularrow');
				if(id != undefined) {
					$('#' + id).tabularInput("deleteRow");
				}
			});
		} else if (response.error != undefined) {
			ReHi.showError ('Oh, snap!', response.error + ' <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
		} else {
			ReHi.showError ('Oh, snap!', 'An unknown error occurred. <a href="mailto:' + $('html').data('email') + '" class="alert-link">Email support</a> for help.');
		}
	}, 'json');

	adminTabular.regSubForm('form.form-students', 'students-update', 'students');
	adminTabular.regSubForm('form.form-deadlines', 'deadlines-update', 'deadlines', 'deadline', 0);
	adminTabular.regSubForm('form.form-wordcounts', 'wordcounts-update', 'word count limits', 'wordcount', 0);
	adminTabular.regSubForm('form.form-funding', 'fundingstatements-update', 'funding statements', 'funding', 0);
	adminTabular.regSubForm('form.form-admins', 'admins-update', 'list of administrators');

	ReHi.regSubForm ($('form.form-email'), $('html').data('uri_root') + '/email.do', function (response, textStatus, jqXHR) {
		if (response != '1') {
			ReHi.showError ('Goshdarnit!', 'Something has gone wrong! (error: ' + response + ')');
		} else {
			ReHi.showSuccess ('Whoop! Whoop!', 'Your emails were sent!')
		}
	});

	$('a[href="#tab-email"]').on ('shown.bs.tab', function (e) {
		autoResize ();
	});

	$('#logout').click (function (e) {
		if (confirm ('If you logout, any unsubmitted changes will be lost!')) {
			window.location.reload ();
		}
	});

	$('textarea').autosize ();
});
