<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Generate a submission preview from MD to HTML

$oUser = I::RH_User ();
$oInput = I::RH_Page_Input ();

try {
	$U = $oUser->login ($oInput->username, $oInput->password);

	if ($oInput->username !== $oInput->saveAs) {
		$oUser->login ($oInput->username, $oInput->password, true);
	}
} catch (\RH\Error $e) {
	print $e->toJson();
	exit;
}

$oSubmission = I::RH_Submission ();

$textMd = \trim ($oInput->text);
$textHtml = !empty ($textMd) ? $oSubmission->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($oInput->references);
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmission->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;