<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of keywords

try {
	$mInput = I::RH_Model_Input ();
	$oSubmission = I::RH_Submission ();

	// for just one user?
	if (isSet ($mInput->user)) {
		$oUser = I::RH_User ();
		$mUser = $oUser->get ($mInput->user);
		$mKeyword = $oSubmission->get ($mUser, false)->getKeywords();
		print $mKeyword->toArrayJson ();
		exit;
	}

	print $oSubmission->getKeywords ()->toJson();
} catch (\RH\Error $e) {
	print $e->toJson ();
}