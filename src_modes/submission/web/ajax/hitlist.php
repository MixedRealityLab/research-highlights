<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have not submitted

$rh = \CDT\RH::i();
$oSubmissionModel = $rh->cdt_submission_model;
$oUserModel = $rh->cdt_user_model;

$oUsers = $oUserModel->getAll (null, function ($oUser) use ($oSubmissionModel) {
	$submission = $oSubmissionModel->get ($oUser->username, false);
	return !isSet ($submission->text) && $oUser->countSubmission;
});

print \CDT\User\Data::toJson ($oUsers);