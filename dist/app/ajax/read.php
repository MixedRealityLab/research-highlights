<?php

$rh = \CDT\RH::i();

$oData = $rh->cdt_data;
$oInput = $rh->cdt_input;
$oUser = $rh->cdt_user;

if(!is_null ($oInput->get('user'))) {
	$users = array ($oUser->get ($oInput->get ('user')));
} else {
	$users = $oUser->getAll ();

	function cmp_users ($a, $b) {
		if ($a['cohort'] < $b['cohort']) {
			return -1;
		} else if ($a['cohort'] > $b['cohort']) {
			return 1;
		}
		
		return strcmp ($a['name'], $b['name']);
	}

	usort ($users, 'cmp_users');
}

$output = array();

foreach ($users as $user) {
	$temp = $oData->get ($user['username'], false);

	if (isset ($temp['text'])) {
		$userData =  $oUser->get ($user['username']);
		
		$temp['text'] = $oData->scanOutput ($temp['text'], $user['username']);
	
		$textMd = $temp['text'];
		$textHtml = !empty ($textMd) ? $oData->markdownToHtml ($textMd) : '<em>No text submitted.</em>';

		$refMd = trim ($oInput->get ('references'));
		$refHtml = !empty ($textMd) ? !empty ($refMd) ?  '<h1>References</h1>' . $oData->markdownToHtml ($refMd) : '' : '';

		$temp['fundingStatement'] = $oUser->getFunding ($user['username']);

		$temp['html'] = $textHtml . $refHtml;
		$temp['references'] = nl2br ($temp['references']);

		$output[] = array_merge ($temp, $userData);
	}
}

die (json_encode ($output));