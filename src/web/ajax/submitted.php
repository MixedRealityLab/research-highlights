<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have submitted

$oUserController = \I::rh_user_controller ();

print $oUserController->getAll (null, function ($oUser) {
	return $oUser->latestVersion && $oUser->countSubmission;
})->toArrayJson();

