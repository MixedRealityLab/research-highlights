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

$mUsers = $oUser->getAll ();
foreach ($mUsers as $mUser) {
	try {
		$tweet = $oSubmission->get ($u, false)->tweet;
		print $mUser->firstName . ',' . $mUser->surname . ',' . $mUser->email . ',' . $tweet . "\n";
	} catch (\RH\Error\NoSubmission $e) {
	}
}
