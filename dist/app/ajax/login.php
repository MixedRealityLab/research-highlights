<?php

$rh = \CDT\RH::i();
$oUser = $rh->cdt_user;

if ($oUser->login ()) {
	$oInput = $rh->cdt_input;
	$oData = $rh->cdt_data;

	die (json_encode (array_merge ($oData->get (), $oUser->get (), array('success' => 1, 'wordCount' => $oUser->getWordCount(), 'fundingStatement' => $oUser->getFunding() ))));
}

die ('-1');
