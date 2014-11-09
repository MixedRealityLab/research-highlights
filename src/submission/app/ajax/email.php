<?php

$oData = \CDT\Submission::data();
$oUser = \CDT\Submission::user();
$oInput = \CDT\Submission::input();

if (!$oUser->login (true)) {
	die ('-1');
}

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$headers .= 'From: "Martin Porcheron" <martin@porcheron.uk>' . "\r\n";
$headers .= 'Reply-To: martin.porcheron@nottingham.ac.uk' . "\r\n";
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

	$address = $user['email'];
	$subject = $oData->scanOutput ($subject, $username);
	$message = $oData->scanOutput ($message, $username);
	$headers = $oData->scanOutput ($headers, $username);

	mail ($address, $subject, $message, $headers);
}

die ('1');