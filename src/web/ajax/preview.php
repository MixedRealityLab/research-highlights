<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

use CDT\Error as Error;

// Generate a submission preview from MD to HTML
// -1 : Not logged in
// -3 : No details on who to save submission as
// -5 : Attempting to masquerade when not admin

$oUserController = \I::rh_user_controller ();
$oPageInput = \I::rh_page_input ();

try {
	$oUser = $oUserController->login ();

	if (!isSet ($oPageInput->saveAs)) {
		throw new Error\InvalidInput ('Must provide saveAs attribute.');
	}

	if ($oPageInput->username !== $oPageInput->saveAs) {
		$oUserController->login (true);
	}
} catch (Error\UserError $e) {
	print $e->toJson();
	exit;
}

$oSubmissionController = \I::rh_submission_controller ();

$textMd = \trim ($oPageInput->text);
$textHtml = !empty ($textMd) ? $oSubmissionController->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($oPageInput->references);
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmissionController->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;