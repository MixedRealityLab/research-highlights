<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users (either all, a cohort, or (not) submitted)
$rh = \CDT\RH::i();
$oInputModel = $rh->cdt_input_model;
$oUserModel = $rh->cdt_user_model;

// Fetch a specific cohort?
$cohort = $oInputModel->get ('cohort');
if (!\is_numeric ($cohort)) {
	$cohort = null;
}

// Fetch those who have submitted, or not?
$submitted = $oInputModel->get ('submitted');
if ($submitted === '1') {
	$submitted = true;
} else if ($submitted === '0') {
	$submitted = false;
} else {
	$submitted = null;
}

// Filter the user list
$oUsers = $oUserModel->getAll (null, function ($oUser) use ($rh, $cohort, $submitted) {
	$oSubmissionModel = $rh->cdt_submission_model;
	$submission = $oSubmissionModel->get ($oUser->username, false);

	$isCohort = is_null ($cohort) ? true : $oUser->cohort === $cohort;
	$isSubmitted = \is_null ($submitted)
		? true 
		: isSet ($submission->text) === $submitted && $oUser->countSubmission;

	return !$oUser->enabled && $isCohort && $isSubmitted;
});

print \CDT\User\Data::toJson ($oUsers);