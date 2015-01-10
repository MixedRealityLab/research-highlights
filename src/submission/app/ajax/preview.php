<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();

$oUser = $rh->cdt_user;
$oInput = $rh->cdt_input;

if (!$oUser->login ()) {
	exit ('-1');
}

if (is_null ($oInput->get('saveAs'))) {
	exit ('-3');
}

if($oInput->get('username') !== $oInput->get ('saveAs') && !$oUser->login (true)) {
	exit ('-5');
}

$oData = $rh->cdt_data;

$textMd = \trim ($oInput->get ('text'));
$textHtml = !empty ($textMd) ? $oData->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($oInput->get ('references'));
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oData->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;