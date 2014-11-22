<?php

$rh = \CDT\RH::i();

$oData = $rh->cdt_data;
$oUser = $rh->cdt_user;
$oInput = $rh->cdt_input;

if (!$oUser->login (true)) {
	die ('-1');
}

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$headers .= 'From: "Martin Porcheron" <martin@porcheron.uk>' . "\r\n";
$headers .= 'Reply-To: cdt-rh@porcheron.uk' . "\r\n";
$headers .= 'X-Mailer: CDT-ReHi/2.0';

if (is_null ($oInput->get ('usernames')) || is_null ($oInput->get ('subject')) || is_null ($oInput->get ('message'))) {
	die ('-2');
}

$usernames = explode ("\n", $oInput->get ('usernames'));
$subject = $oInput->get ('subject');
$message = nl2br ($oInput->get ('message'));

foreach ($usernames as $username) {
	$username = trim ($username);
	
	if (empty ($username)) {
		continue;
	}

	$user = $oUser->get ($username);
	if (is_null ($user) || empty ($user)) {
		continue;
	}

	$mAddress = $user['email'];
	$mSubject = $oData->scanOutput ($subject, $username);
	$mMessage = $oData->scanOutput ($message, $username);
	$mHeaders = $oData->scanOutput ($headers, $username);

	mail ($mAddress, $mSubject, $mMessage, $mHeaders);
}

die ('1');