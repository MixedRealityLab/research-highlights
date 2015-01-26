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

$oSubmissionController = \I::rh_submission_controller ();
$oUserController = \I::rh_user_controller ();
$oPageInput = \I::rh_page_input ();
$oUtilsEmail = \I::rh_utils_email ();

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

$usernames = \explode ("\n", \trim ($oPageInput->usernames));
$subject = $oPageInput->subject;
$message = \nl2br ($oPageInput->message);

print $oUtilsEmail->sendAll ($usernames, $subject, \strip_tags ($message), $message) ? '1' : '-1';