<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Validate login credentials

\header ('Content-type: application/json');

\define ('NO_CACHE', true);

try {
	$mInput = I::RH_Model_Input ();
	$cUser = I::RH_User ();

	$mUser = $cUser->login ($mInput->username, $mInput->password);

	$cSubmission = I::RH_Submission ();

	// if admin, are we masquerading
	if ($mUser->admin && isSet ($mInput->profile)) {
		$mUser = $cUser->get (\strtolower ($mInput->profile));
		$cUser->overrideLogin ($mUser);
	}

	// gather the data to populate the submission form
	print $mUser
		->merge ($cSubmission->get ($mUser))
		->merge (array ('success' => 1))
		->toJson ();
} catch (\RH\Error $e) {
	print $e->toJson ();
}