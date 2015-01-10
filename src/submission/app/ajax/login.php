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
$oUser = $rh->cdt_user;

if ($oUser->login ()) {
	$oInput = $rh->cdt_input;
	$oData = $rh->cdt_data;

	// if admin, are we masquerading
	if ($oUser->login (true)) {
		$override = $oInput->get ('profile');

		if (!is_null ($override)) {
			$override = \strtolower ($override);
			$temp = $oUser->get ($override);
			if (empty ($temp)) {
				print '-4';
				exit;
			}

			$oUser->overrideLogin ($override);
		} 
	}

	// gather the data to populate the submission form
	$data = array (
	               'success' => 1,
	               'wordCount' => $oUser->getWordCount (),
	               'fundingStatement' => $oUser->getFunding ());

	print \json_encode (\array_merge ($oData->get (), $oUser->get (), $data));
	exit;
}

print '-1';
exit;