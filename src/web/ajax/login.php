<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Validate login credentials

try {
	$oInput = I::RH_Page_Input ();
	$oUser = I::RH_User ();

	$U = $oUser->login ($oInput->username, $oInput->password);

	$oSubmission = I::RH_Submission ();

	// if admin, are we masquerading
	if ($U->admin && isSet ($oInput->profile)) {
		$U = $oUser->get (\strtolower ($oInput->profile));
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