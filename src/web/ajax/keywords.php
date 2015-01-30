<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of keywords

try {
	$oInput = I::RH_Page_Input ();
	$oUser = I::RH_User ();
	$oSubmission = I::RH_Submission ();

	// for just one user?
	if (isSet ($oInput->user)) {
		$U = $oUser->get ($oInput->user);
		$K = $oSubmission->get ($U, false)->getKeywords();
		print $K->toArrayJson ();
		exit;
	}

	// is there a saved copy of all keywords?
	$file = DIR_DAT . '/keywords.txt';
	if (\is_file ($file) && \filemtime ($file) + KEY_CACHE < \date ('U')) {
		print @\file_get_contents ($file)->unserialize ()->toJson ();
		exit;
	}

	// generate list of keywords for everyone
	if (isSet ($oInput->user)) {
		$U = $oUser->get ($oInput->user);
		$K = $oSubmission->get ($U, false)->getKeywords();
		print $K->toArrayJson ();
		exit;
	}

	$Us = I::RH_User ()->getAll (null, function ($U) {
		return $U->latestVersion && $U->countSubmission;
	});

	$Ks = new \RH\Submission\Keywords ();
	foreach ($Us as $U) {
		$S = $oSubmission->get ($U, false);
		foreach ($S->getKeywords () as $keyword) {
			if (!isSet ($Ks->$keyword)) {
				$Ks->$keyword = new \RH\User\Users();
			}
			$Ks->$keyword->offsetSet ($U->username, $U);
		}
	}
	$Ks->ksort ();
	$json = $Ks->toJson();

	@\file_put_contents ($file, $Ks->serialize ());
	@\chmod ($file, 0777);

	print $json;
} catch (\RH\Error $e) {
	print $e->toJson ();
}