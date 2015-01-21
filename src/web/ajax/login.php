<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Validate login credentials
// -1 : Failed login
// -4 : Cannot masquerade as given user (doesn't exist)

$rh = \CDT\RH::i();
$oUserController = $rh->cdt_user_controller;

if ($oUserController->login ()) {
	$oPageInput = $rh->cdt_page_input;
	$oSubmissionController = $rh->cdt_submission_controller;

	// if admin, are we masquerading
	if ($oUserController->login (true) && isSet ($oPageInput->profile)) {
		$override = $oPageInput->profile;

		$override = \strtolower ($override);
		$temp = $oUserController->get ($override);
		if (empty ($temp)) {
			print '-4';
			exit;
		}

		$oUserController->overrideLogin ($override);
	}

	// gather the data to populate the submission form
	print $oUserController->get ()
		->merge ($oSubmissionController->get ())
		->merge (array ('success' => 1,
				   'wordCount' => $oUserController->getWordCount (),
				   'fundingStatement' => $oUserController->getFunding ()))
		->toJson ();
	exit;
}

print '-1';
exit;