<?php

$oUser = \CDT\Submission::user();

if ($oUser->login ()) {
	$oInput = \CDT\Submission::input();
	$oData = \CDT\Submission::data();

	die (json_encode (array_merge ($oData->get (), $oUser->get (), array('success' => 1, 'wordCount' => $oUser->getWordCount(), 'fundingStatement' => $oUser->getFunding() ))));
}

die ('-1');
