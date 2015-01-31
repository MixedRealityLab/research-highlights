<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all submissions, or a single submission for reading

try {
	$oSubmission = I::RH_Submission ();
	$mInput = I::RH_Model_Input ();
	$oUser = I::RH_User ();

	// Get the users for which we want to return their submission
	if (isSet ($mInput->user)) {
		$mUsers = array ($oUser->get ($mInput->user));

	} else if (isSet ($mInput->cohort)) {
		$cohort = $mInput->cohort;
		$mUsers = $oUser->getAll (null, function ($mUser) use ($cohort) {
			return $mUser->countSubmission && $mUser->cohort === $cohort;
		});

	} else if (isSet ($mInput->keywords)) {
		// is there a saved copy of all keywords?
		$keywords = @\explode (',', $mInput->keywords);
		$mKeywords = $oSubmission->getKeywords ();
		$mUsers = new \RH\Model\Users();

		foreach ($keywords as $keyword) {
			if(!empty ($keyword) && isSet ($mKeywords[$keyword])) {
				$mUsers->merge ($mKeywords->$keyword);
			}
		}

	} else {
		$mUsers = $oUser->getAll (null, function ($mUser) {
			return $mUser->countSubmission;
		});
	}

	// Format the submission for output
	$output = array();
	foreach ($mUsers as $mUser) {
		try {
			$mSubmission = $oSubmission->get ($mUser, false);

			$mSubmission->text = $mUser->makeSubsts ($mSubmission->text);
		
			$textMd = $mSubmission->text;
			$textHtml = !empty ($textMd) ? $oSubmission->markdownToHtml ($textMd) : '<em>No text submitted.</em>';

			$refMd = \trim ($mSubmission->references);
			$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmission->markdownToHtml ($refMd) : '';
			
			$pubMd = \trim ($mSubmission->publications);
			$pubHtml = !empty ($pubMd) ? '<h1>Publications in the Last Year</h1>' . $oSubmission->markdownToHtml ($pubMd) : '';

			$mSubmission->html = $textHtml . $refHtml . $pubHtml;

			$output[] = \array_merge ($mSubmission->toArray (), $mUser->toArray ());
		} catch (\RH\Error\NoSubmission $e) {
		}
	}

	print \json_encode ($output);
	exit;
} catch (\RH\Error $e) {
	print $e->toJson ();
}