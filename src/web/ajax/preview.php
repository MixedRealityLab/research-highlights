<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Generate a submission preview from MD to HTML

$cUser = I::RH_User ();
$mInput = I::RH_Model_Input ();

try {
	$mUser = $cUser->login ($mInput->username, $mInput->password);

	if ($mInput->username !== $mInput->saveAs) {
		$cUser->login ($mInput->username, $mInput->password, true);
	}
} catch (\RH\Error $e) {
	print $e->toJson();
	exit;
}

$cSubmission = I::RH_Submission ();

$textMd = \trim ($mInput->text);
$textHtml = !empty ($textMd) ? \RH\Submission::markdownToHtml ($textMd) : '<em>No text.</em>';

$refMd = \trim ($mInput->references);
$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . \RH\Submission::markdownToHtml ($refMd) : '';

print $textHtml . $refHtml;