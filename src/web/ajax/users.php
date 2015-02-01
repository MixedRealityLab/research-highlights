<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users (either all, a cohort, or (not) submitted)

\header ('Content-type: application/json');

try {
	$mInput = I::RH_Model_Input ();
	$cUser = I::RH_User ();

	// Fetch a specific cohort?
	if (!isSet ($mInput->cohort) || !\is_numeric ($mInput->cohort)) {
		$cohort = null;
	} else {
		$cohort = $mInput->cohort;
	}

	// Fetch those who have submitted, or not?
	try {
		$submitted = $mInput->submitted;
		if ($submitted === '1') {
			$submitted = true;
		} else if ($submitted === '0') {
			$submitted = false;
		}
	} catch (\RH\Error\NoField $e) {
		$submitted = null;
	}

	// Filter the user list
	print $cUser->getAll (null, function ($mUser) use ($cohort, $submitted) {
		$cSubmission = I::RH_Submission ();
		$isCohort = \is_null ($cohort) ? true : $mUser->cohort === $cohort;
		
		if (\is_null ($submitted)) {
			$isSubmitted = true;
		} else {
			try {
				$mSubmission = $cSubmission->get ($mUser, false);
				$isSubmitted = $submitted === true;
			} catch (\RH\Error\NoSubmission $e) {
				$isSubmitted = $submitted === false;
			}
		}

		return $mUser->enabled && $mUser->countSubmission && $isCohort && $isSubmitted;
	})->toArrayJson();
} catch (\RH\Error $e) {
	print $e->toJson ();
}