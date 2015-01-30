<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have submitted

try {
	print I::RH_User ()->getAll (null, function ($U) {
		return $U->latestVersion && $U->countSubmission;
	})->toArrayJson ();
} catch (\RH\Error $e) {
	print $e->toJson ();
}