<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Validate login credentials

try {
	$mInput = I::RH_Model_Input ();
	$oUser = I::RH_User ();

	$U = $oUser->login ($mInput->username, $mInput->password);

	$oSubmission = I::RH_Submission ();

	// if admin, are we masquerading
	if ($U->admin && isSet ($mInput->profile)) {
		$U = $oUser->get (\strtolower ($mInput->profile));
		$oUser->overrideLogin ($U);
	}

	// gather the data to populate the submission form
	print $U
		->merge ($oSubmission->get ($U))
		->merge (array ('success' => 1))
		->toJson ();
} catch (\RH\Error $e) {
	print $e->toJson ();
}