<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Generate a submission preview from MD to HTML

$oUser = I::RH_User ();
$mInput = I::RH_Model_Input ();

try {
	$mUser = $oUser->login ($mInput->username, $mInput->password);

	if ($mInput->username !== $mInput->saveAs) {
		$oUser->login ($mInput->username, $mInput->password, true);
	}
} catch (\RH\Error $e) {
	print $e->toJson();
	exit;
}

$oSubmission = I::RH_Submission ();

$textMd = \trim ($mInput->text);
$textHtml = !empty ($textMd) ? $oSubmission->markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($mInput->references);
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmission->markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;