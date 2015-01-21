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
$oUserModel = $rh->cdt_user_model;

if ($oUserModel->login ()) {
	$oInputModel = $rh->cdt_input_model;
	$oSubmissionModel = $rh->cdt_submission_model;

	// if admin, are we masquerading
	if ($oUserModel->login (true)) {
		$override = $oInputModel->get ('profile');

		if (!is_null ($override)) {
			$override = \strtolower ($override);
			$temp = $oUserModel->get ($override);
			if (empty ($temp)) {
				print '-4';
				exit;
			}

			$oUserModel->overrideLogin ($override);
		} 
	}

	// gather the data to populate the submission form
	print $oUserModel->get ()
		->merge ($oSubmissionModel->get ())
		->merge (array ('success' => 1,
				   'wordCount' => $oUserModel->getWordCount (),
				   'fundingStatement' => $oUserModel->getFunding ()))
		->toJson ();
	exit;
}

print '-1';
exit;