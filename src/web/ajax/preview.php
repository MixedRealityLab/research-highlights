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
$oUserController = $rh->cdt_user_controller;
$oPageInput = $rh->cdt_page_input;

if (!$oUserController->login ()) {
	print '-1';
	exit;
}

if (!isSet ($oPageInput->saveAs)) {
	print '-3';
	exit;
}

if ($oPageInput->username !== $oPageInput->saveAs
	&& !$oUserController->login (true)) {
	print '-5';
	exit;
}

$oSubmissionController = $rh->cdt_submission_controller;

$textMd = \trim ($oPageInput->text);
$textHtml = !empty ($textMd) ? $oSubmissionController->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($oPageInput->references);
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmissionController->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;