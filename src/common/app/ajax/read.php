<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();

$oData = $rh->cdt_data;
$oInput = $rh->cdt_input;
$oUser = $rh->cdt_user;

if(!is_null ($oInput->get('user'))) {
	$users = array ($oUser->get ($oInput->get ('user')));
} else {
	$users = $oUser->getAll (null, function ($user) {
		return $user['countSubmission'];
	});
}

$output = array();
foreach ($users as $user) {
	$temp = $oData->get ($user['username'], false);

	if (isSet ($temp['text'])) {
		$userData =  $oUser->get ($user['username']);
		
		$temp['text'] = $oData->scanOutput ($temp['text'], $user['username']);
	
		$textMd = $temp['text'];
		$textHtml = !empty ($textMd) ? $oData->markdownToHtml ($textMd) : '<em>No text submitted.</em>';

		$refMd = \trim ($temp['references']);
		$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oData->markdownToHtml ($refMd) : '';
		
		$pubMd = \trim ($temp['publications']);
		$pubHtml = !empty ($pubMd) ? '<h1>Publications in the Last Year</h1>' . $oData->markdownToHtml ($pubMd) : '';

		$temp['html'] = $textHtml . $refHtml . $pubHtml;
		$temp['fundingStatement'] = $oUser->getFunding ($user['username']);

		$output[] = array_merge ($temp, $userData);
	}
}

exit (\json_encode ($output));