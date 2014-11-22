<?php

$rh = \CDT\RH::i();

$oUser = $rh->cdt_user;

if (!$oUser->login ()) {
	die ('-1');
}

$oInput = $rh->cdt_input;
$oData = $rh->cdt_data;

$textMd = trim ($oInput->get ('text'));
$textHtml = !empty ($textMd) ? $oData->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = trim ($oInput->get ('references'));
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oData->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;