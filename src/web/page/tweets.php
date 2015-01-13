<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all tweets
$rh = \CDT\RH::i();
$oSubmissionModel = $rh->cdt_submission_model;
$oUserModel = $rh->cdt_user_model;

\header ('Content-Type: text/csv');

$oUsers = $oUserModel->getAll ();
foreach ($oUsers as $oUser) {
	$temp = $oSubmissionModel->get ($oUser->username, false);

	if (isSet ($temp->text)) {
		print $oUser->firstName . ',' . $oUser->surname . ',' . $oUser->email . ',' . $temp->tweet . "\n";
	}
}
