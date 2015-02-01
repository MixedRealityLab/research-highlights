<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all tweets

$cSubmission = I::RH_Submission ();
$cUser = I::RH_User ();

\header ('Content-Type: text/csv');

$mUsers = $cUser->getAll ();
foreach ($mUsers as $mUser) {
	try {
		$tweet = $cSubmission->get ($u, false)->tweet;
		print $mUser->firstName . ',' . $mUser->surname . ',' . $mUser->email . ',' . $tweet . "\n";
	} catch (\RH\Error\NoSubmission $e) {
	}
}
