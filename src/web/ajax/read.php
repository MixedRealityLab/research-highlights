<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all submissions, or a single submission for reading

$oSubmissionController = \I::rh_submission_controller ();
$oPageInput = \I::rh_page_input ();
$oUserController = \I::rh_user_controller ();

// Get the users for which we want to return their submission
if (isSet ($oPageInput->user)) {
	$oUsers = array ($oUserController->get ($oPageInput->user));

} else if (isSet ($oPageInput->cohort)) {
	$cohort = $oPageInput->cohort;
	$oUsers = $oUserController->getAll (null, function ($user) use ($cohort) {
		return $user->countSubmission && $user->cohort === $cohort;
	});

} else if (isSet ($oPageInput->keywords)) {
	// is there a saved copy of all keywords?
	$keywords = @\explode (',', $oPageInput->keywords);
	foreach ($keywords as $keyword) {
		$keywords[] = \str_replace ('_', ' ', $keyword);
	}

	$allKeywords = $oSubmissionController->getKeywords ()->toArray();
	$oUsers = array();

	foreach ($keywords as $keyword) {
		if(!empty ($keyword) && isSet ($allKeywords[$keyword])) {
			foreach ($allKeywords[$keyword]['users'] as $k => $user) {
				$oUsers[$user] = $oUserController->get ($user);
			}
		}
	}
	$oUsers = \array_values ($oUsers);

} else {
	$oUsers = $oUserController->getAll (null, function ($user) {
		return $user->countSubmission;
	});
}

// Format the submission for output
$output = array();
foreach ($oUsers as $oUser) {
	try {
		$oSubmission = $oSubmissionController->get ($oUser, false);

		$oSubmission->text = $oUser->makeSubsts ($oSubmission->text);
	
		$textMd = $oSubmission->text;
		$textHtml = !empty ($textMd) ? $oSubmissionController->markdownToHtml ($textMd) : '<em>No text submitted.</em>';

		$refMd = \trim ($oSubmission->references);
		$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmissionController->markdownToHtml ($refMd) : '';
		
		$pubMd = \trim ($oSubmission->publications);
		$pubHtml = !empty ($pubMd) ? '<h1>Publications in the Last Year</h1>' . $oSubmissionController->markdownToHtml ($pubMd) : '';

		$oSubmission->html = $textHtml . $refHtml . $pubHtml;

		$output[] = \array_merge ($oSubmission->toArray (), $oUser->toArray ());
	} catch (\RH\Error\NoSubmission $e) {

	}
}

print \json_encode ($output);
exit;