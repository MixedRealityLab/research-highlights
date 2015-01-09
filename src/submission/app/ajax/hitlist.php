<?php

$rh = \CDT\RH::i();

$oData = $rh->cdt_data;
$oUser = $rh->cdt_user;

$users = $oUser->getAll (null, function ($user) use ($oData) {
	$userData = $oData->get ($user['username'], false);
	return !isSet ($userData['text']) && $user['countSubmission'] == '1';
});

die (\json_encode ($users));