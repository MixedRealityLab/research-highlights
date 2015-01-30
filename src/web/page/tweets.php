<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all tweets

$oSubmission = I::RH_Submission ();
$oUser = I::RH_User ();

\header ('Content-Type: text/csv');

$Us = $oUser->getAll ();
foreach ($Us as $U) {
	try {
		$tweet = $oSubmission->get ($u, false)->tweet;
		print $U->firstName . ',' . $U->surname . ',' . $U->email . ',' . $tweet . "\n";
	} catch (\RH\Error\NoSubmission $e) {
	}
}
