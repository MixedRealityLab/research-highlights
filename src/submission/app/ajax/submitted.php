<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have submitted

$rh = \CDT\RH::i();
$oData = $rh->cdt_data;
$oUser = $rh->cdt_user;

$users = $oUser->getAll (null, function ($user) use ($oData) {
	$userData = $oData->get ($user['username'], false);
	return isSet ($userData['text']) && $user['countSubmission'];
});

print \json_encode ($users);