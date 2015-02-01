<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Send an email to users

\header ('Content-type: application/json');

try {
	$cSubmission = I::RH_Submission ();
	$cUser = I::RH_User ();
	$mInput = I::RH_Model_Input ();
	$oEmail = I::RH_Email ();

	$mUser = $cUser->login ($mInput->username, $mInput->password, true);

	$from = '"'. $mUser->firstName . ' ' . $mUser->surname .'" <'. $mUser->email .'>';
	$replyTo = '"'. SITE_NAME .'" <'. EMAIL .'>';
	$oEmail->setHeaders ($from, $replyTo);

	$usernames = \preg_split ('/[\n|\r\n|\r]/', \trim ($mInput->usernames), null, PREG_SPLIT_NO_EMPTY);
	$subject = $mInput->subject;
	$message = \nl2br ($mInput->message);

	print $oEmail->sendAll ($usernames, $subject, \strip_tags ($message), $message) ? '1' : '-1';
} catch (\RH\Error $e) {
	print $e->toJson ();
}