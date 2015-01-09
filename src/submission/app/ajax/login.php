<?php

$rh = \CDT\RH::i();
$oUser = $rh->cdt_user;

if ($oUser->login ()) {
	$oInput = $rh->cdt_input;
	$oData = $rh->cdt_data;

	if ($oUser->login (true)) {
		$override = $oInput->get ('profile');

		if (!is_null ($override)) {
			$override = \strtolower ($override);
			$temp = $oUser->get ($override);
			if (empty ($temp)) {
				die('-4');
			}

			$oUser->overrideLogin ($override);
		} 
	}

	die (\json_encode (\array_merge ($oData->get (), $oUser->get (), array ('success' => 1, 'wordCount' => $oUser->getWordCount (), 'fundingStatement' => $oUser->getFunding ()))));
}

die ('-1');
