<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all tweets
$rh = \CDT\RH::i();
$oSubmissionController = $rh->cdt_submission_controller;
$oUserController = $rh->cdt_user_controller;

\header ('Content-Type: text/csv');

$oUsers = $oUserController->getAll ();
foreach ($oUsers as $oUser) {
	$temp = $oSubmissionController->get ($oUser->username, false);

	if (isSet ($temp->text)) {
		print $oUser->firstName . ',' . $oUser->surname . ',' . $oUser->email . ',' . $temp->tweet . "\n";
	}
}
