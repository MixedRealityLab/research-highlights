<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Generate a submission preview from MD to HTML
// -1 : Not logged in
// -3 : No details on who to save submission as
// -5 : Attempting to masquerade when not admin

$rh = \CDT\RH::i();
$oUser = $rh->cdt_user;
$oInput = $rh->cdt_input;

if (!$oUser->login ()) {
	print '-1';
	exit;
}

if (is_null ($oInput->get('saveAs'))) {
	print '-3';
	exit;
}

if ($oInput->get('username') !== $oInput->get ('saveAs')
	&& !$oUser->login (true)) {
	print '-5';
	exit;
}

$oData = $rh->cdt_data;

$textMd = \trim ($oInput->get ('text'));
$textHtml = !empty ($textMd) ? $oData->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($oInput->get ('references'));
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oData->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;
exit;