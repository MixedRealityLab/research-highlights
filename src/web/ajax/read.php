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
		$Us = array ($oUser->get ($mInput->user));

	} else if (isSet ($mInput->cohort)) {
		$cohort = $mInput->cohort;
		$Us = $oUser->getAll (null, function ($U) use ($cohort) {
			return $U->countSubmission && $U->cohort === $cohort;
		});

	} else if (isSet ($mInput->keywords)) {
		// is there a saved copy of all keywords?
		$keywords = @\explode (',', $mInput->keywords);
		$Ks = $oSubmission->getKeywords ();
		$Us = new \RH\Model\Users();

		foreach ($keywords as $keyword) {
			if(!empty ($keyword) && isSet ($Ks[$keyword])) {
				$Us->merge ($Ks->$keyword);
			}
		}

	} else {
		$Us = $oUser->getAll (null, function ($U) {
			return $U->countSubmission;
		});
	}

	// Format the submission for output
	$output = array();
	foreach ($Us as $U) {
		try {
			$S = $oSubmission->get ($U, false);

			$S->text = $U->makeSubsts ($S->text);
		
			$textMd = $S->text;
			$textHtml = !empty ($textMd) ? $oSubmission->markdownToHtml ($textMd) : '<em>No text submitted.</em>';

			$refMd = \trim ($S->references);
			$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmission->markdownToHtml ($refMd) : '';
			
			$pubMd = \trim ($S->publications);
			$pubHtml = !empty ($pubMd) ? '<h1>Publications in the Last Year</h1>' . $oSubmission->markdownToHtml ($pubMd) : '';

			$S->html = $textHtml . $refHtml . $pubHtml;

			$output[] = \array_merge ($S->toArray (), $U->toArray ());
		} catch (\RH\Error\NoSubmission $e) {
		}
	}

	print \json_encode ($output);
	exit;
} catch (\RH\Error $e) {
	print $e->toJson ();
}