<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Send an email to users

try {
	$oSubmission = I::RH_Submission ();
	$oUser = I::RH_User ();
	$mInput = I::RH_Model_Input ();
	$oEmail = I::RH_Email ();

	$U = $oUser->login ($mInput->username, $mInput->password, true);

	$from = '"'. $U->firstName . ' ' . $U->surname .'" <'. $U->email .'>';
	$replyTo = '"'. SITE_NAME .'" <'. EMAIL .'>';
	$oEmail->setHeaders ($from, $replyTo);

	$usernames = \explode ("\n", \trim ($mInput->usernames));
	$subject = $mInput->subject;
	$message = \nl2br ($mInput->message);

	print $oEmail->sendAll ($usernames, $subject, \strip_tags ($message), $message) ? '1' : '-1';
} catch (\RH\Error $e) {
	print $e->toJson ();
}