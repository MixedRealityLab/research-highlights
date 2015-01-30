<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users (either all, a cohort, or (not) submitted)

try {
	$oInput = I::RH_Page_Input ();
	$oUser = I::RH_User ();

	// Fetch a specific cohort?
	if (!isSet ($oInput->cohort) || !\is_numeric ($oInput->cohort)) {
		$cohort = null;
	} else {
		$cohort = $oInput->cohort;
	}

	// Fetch those who have submitted, or not?
	try {
		$submitted = $oInput->submitted;
		if ($submitted === '1') {
			$submitted = true;
		} else if ($submitted === '0') {
			$submitted = false;
		}
	} catch (\RH\Error\NoField $e) {
		$submitted = null;
	}

	// Filter the user list
	print $oUser->getAll (null, function ($U) use ($cohort, $submitted) {
		$oSubmission = I::RH_Submission ();
		$isCohort = \is_null ($cohort) ? true : $U->cohort === $cohort;
		
		if (\is_null ($submitted)) {
			$isSubmitted = true;
		} else {
			try {
				$S = $oSubmission->get ($U, false);
				$isSubmitted = $submitted === true;
			} catch (\RH\Error\NoSubmission $e) {
				$isSubmitted = $submitted === false;
			}
		}

		return $U->enabled && $U->countSubmission && $isCohort && $isSubmitted;
	})->toArrayJson();
} catch (\RH\Error $e) {
	print $e->toJson ();
}