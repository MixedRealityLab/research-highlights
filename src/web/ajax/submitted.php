<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have submitted

\header ('Content-type: application/json');

try {
	print I::RH_User ()->getAll (null, function ($mUser) {
		return $mUser->latestVersion && $mUser->countSubmission;
	})->toArrayJson ();
} catch (\RH\Error $e) {
	print $e->toJson ();
}