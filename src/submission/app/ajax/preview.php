<?php

$rh = \CDT\RH::i();

$oUser = $rh->cdt_user;
$oInput = $rh->cdt_input;

if (!$oUser->login ()) {
	die ('-1');
}

if (is_null ($oInput->get('saveAs'))) {
	die ('-3');
}

if($oInput->get('username') !== $oInput->get ('saveAs') && !$oUser->login (true)) {
	die ('-5');
}

$oData = $rh->cdt_data;

$textMd = \trim ($oInput->get ('text'));
$textHtml = !empty ($textMd) ? $oData->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($oInput->get ('references'));
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oData->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;