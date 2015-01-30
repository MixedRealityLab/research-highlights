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
	$oInput = I::RH_Page_Input ();
	$oEmail = I::RH_Utils_Email ();

	$U = $oUser->login ($oInput->username, $oInput->password, true);

	$from = '"'. $U->firstName . ' ' . $U->surname .'" <'. $U->email .'>';
	$replyTo = EMAIL;
	$oEmail->setHeaders ($from, $replyTo);

	$usernames = \explode ("\n", \trim ($oInput->usernames));
	$subject = $oInput->subject;
	$message = \nl2br ($oInput->message);

	print $oEmail->sendAll ($usernames, $subject, \strip_tags ($message), $message) ? '1' : '-1';
} catch (\RH\Error $e) {
	print $e->toJson ();
}