<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();

$oData = $rh->cdt_data;
$oUser = $rh->cdt_user;
$oInput = $rh->cdt_input;
$oEmail = $rh->cdt_email;

if (!$oUser->login (true)) {
	exit ('-1');
}

if (is_null ($oInput->get ('usernames')) || is_null ($oInput->get ('subject')) || is_null ($oInput->get ('message'))) {
	exit ('-2');
}

$user = $oUser->get ();
$oEmail->setHeaders ('"'. $user['name'] .'" <'. $user['email'] .'>', 'cdt-rh@lists.porcheron.uk');

$usernames = \explode ("\n", $oInput->get ('usernames'));
$subject = $oInput->get ('subject');
$message = \nl2br ($oInput->get ('message'));

$oEmail->sendAll ($usernames, $subject, \strip_tags ($message), $message);

exit ('1');