<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Send an email to users
//  1 : Success
// -1 : Not logged in as admin
// -2 : Incomplete form

$rh = \CDT\RH::i();
$oSubmissionController = $rh->cdt_submission_controller;
$oUserController = $rh->cdt_user_controller;
$oPageInput = $rh->cdt_page_input;
$oUtilsEmail = $rh->cdt_utils_email;

if (!$oUserController->login (true)) {
	print '-1';
	exit;
}

if (!isSet ($oPageInput->usernames)
	|| !isSet ($oPageInput->subject)
	|| !isSet ($oPageInput->message)) {
	print '-2';
	exit;
}

$oUser = $oUserController->get ();

$from = '"'. $oUser->firstName . ' ' . $oUser->surname .'" <'. $oUser->email .'>';
$replyTo = 'cdt-rh@lists.porcheron.uk';
$oUtilsEmail->setHeaders ($from, $replyTo);

$usernames = \explode ("\n", $oPageInput->usernames);
$subject = $oPageInput->subject;
$message = \nl2br ($oPageInput->message);
$oUtilsEmail->sendAll ($usernames, $subject, \strip_tags ($message), $message);

print '1';