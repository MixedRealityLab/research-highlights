<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have submitted

$rh = \CDT\RH::i();
$oSubmissionController = $rh->cdt_submission_controller;
$oUserController = $rh->cdt_user_controller;

print $oUserController->getAll (null, function ($oUser) use ($oSubmissionController) {
	$submission = $oSubmissionController->get ($oUser, false);
	return isSet ($submission->text) && $oUser->countSubmission;
})->toArrayJson();

