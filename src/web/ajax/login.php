<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Validate login credentials

$oUserController = \I::rh_user_controller ();

try {
	$oUser = $oUserController->login ();

	$oPageInput = \I::rh_page_input ();
	$oSubmissionController = \I::rh_submission_controller ();

	// if admin, are we masquerading
	if ($oUser->admin && isSet ($oPageInput->profile)) {
		$username = \strtolower ($oPageInput->profile);
		$oUser = $oUserController->get ($username);
		$oUserController->overrideLogin ($oUser);
	}

	// gather the data to populate the submission form
	print $oUser
		->merge ($oSubmissionController->get ($oUser))
		->merge (array ('success' => 1))
		->toJson ();
} catch (\RH\Error\UserError $e) {
	print $e->toJson ();
}