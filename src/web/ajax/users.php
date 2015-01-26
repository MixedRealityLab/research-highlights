<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users (either all, a cohort, or (not) submitted)

$rh = \CDT\RH::i();
$oPageInput = $rh->cdt_page_input;
$oUserController = $rh->cdt_user_controller;

// Fetch a specific cohort?
if (!isSet ($oPageInput->cohort) || !\is_numeric ($oPageInput->cohort)) {
	$cohort = null;
} else {
	$cohort = $oPageInput->cohort;
}

// Fetch those who have submitted, or not?
$submitted = $oPageInput->submitted;
if ($submitted === '1') {
	$submitted = true;
} else if ($submitted === '0') {
	$submitted = false;
} else {
	$submitted = null;
}

// Filter the user list
$oUsers = $oUserController->getAll (null, function ($oUser) use ($rh, $cohort, $submitted) {
	$oSubmissionController = $rh->cdt_submission_controller;
	$submission = $oSubmissionController->get ($oUser->username, false);

	$isCohort = \is_null ($cohort) ? true : $oUser->cohort === $cohort;
	$isSubmitted = \is_null ($submitted)
		? true 
		: isSet ($submission->text) === $submitted && $oUser->countSubmission;

	return !$oUser->enabled && $isCohort && $isSubmitted;
});

print \CDT\User\Data::toJson ($oUsers);