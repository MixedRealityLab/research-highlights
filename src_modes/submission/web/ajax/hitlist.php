<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have not submitted

$rh = \CDT\RH::i();
$oSubmissionController = $rh->cdt_submission_controller;
$oUserController = $rh->cdt_user_controller;

$oUsers = $oUserController->getAll (null, function ($oUser) use ($oSubmissionController) {
	$submission = $oSubmissionController->get ($oUser->username, false);
	return !isSet ($submission->text) && $oUser->countSubmission;
});

print \CDT\User\Data::toJson ($oUsers);