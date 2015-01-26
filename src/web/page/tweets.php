<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all tweets

$oSubmissionController = I::RH_Submission_Controller ();
$oUserController = I::RH_User_Controller ();

\header ('Content-Type: text/csv');

$oUsers = $oUserController->getAll ();
foreach ($oUsers as $oUser) {
	try {
		$tweet = $oSubmissionController->get ($oUser, false)->tweet;
		print $oUser->firstName . ',' . $oUser->surname . ',' . $oUser->email . ',' . $tweet . "\n";
	} catch (\RH\Error\NoSubmission $e) {
	}
}
