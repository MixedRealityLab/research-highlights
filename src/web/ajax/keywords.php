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
	$oUser = I::RH_User ();
	$oSubmission = I::RH_Submission ();

	// for just one user?
	if (isSet ($mInput->user)) {
		$U = $oUser->get ($mInput->user);
		$K = $oSubmission->get ($U, false)->getKeywords();
		print $K->toArrayJson ();
		exit;
	}

	print $oSubmission->getKeywords ()->toJson();
} catch (\RH\Error $e) {
	print $e->toJson ();
}