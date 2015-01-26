<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users (either all, a cohort, or (not) submitted)

$oPageInput = \I::rh_page_input ();
$oUserController = \I::rh_user_controller ();

// Fetch a specific cohort?
if (!isSet ($oPageInput->cohort) || !\is_numeric ($oPageInput->cohort)) {
	$cohort = null;
} else {
	$cohort = $oPageInput->cohort;
}

// Fetch those who have submitted, or not?
try {
	$submitted = $oPageInput->submitted;
	if ($submitted === '1') {
		$submitted = true;
	} else if ($submitted === '0') {
		$submitted = false;
	}
} catch (\RH\Error\NoField $e) {
	$submitted = null;
}

// Filter the user list
$oUsers = $oUserController->getAll (null, function ($oUser) use ($cohort, $submitted) {
	$oSubmissionController = \I::rh_submission_controller ();

	$isCohort = \is_null ($cohort) ? true : $oUser->cohort === $cohort;
	
	if (\is_null ($submitted)) {
		$isSubmitted = true;
	} else {
		try {
			$submission = $oSubmissionController->get ($oUser, false);
			$isSubmitted = $submitted === true;
		} catch (\RH\Error\NoSubmission $e) {
			$isSubmitted = $submitted === false;
		}
	}

	return $oUser->enabled && $oUser->countSubmission 
		&& $isCohort && $isSubmitted;
});

print $oUsers->toArrayJson();