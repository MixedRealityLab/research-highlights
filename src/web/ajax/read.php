<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all submissions, or a single submission for reading

$rh = \CDT\RH::i();
$oSubmissionModel = $rh->cdt_submission_model;
$oInputModel = $rh->cdt_input_model;
$oUserModel = $rh->cdt_user_model;

// Get the users for which we want to return their submission
if(!is_null ($oInputModel->get('user'))) {
	$oUsers = array ($oUserModel->get ($oInputModel->get ('user')));
} else if(!is_null ($oInputModel->get('cohort'))) {
	$cohort = $oInputModel->get('cohort');
	$oUsers = $oUserModel->getAll (null, function ($user) use ($cohort) {
		return $user->countSubmission && $user->cohort === $cohort;
	});
} else if(!is_null ($oInputModel->get('keywords'))) {
	
	// is there a saved copy of all keywords?
	$keywords = @\explode (',', $oInputModel->get('keywords'));
	foreach($keywords as $keyword) {
		$keywords[] = \str_replace ('_', ' ', $keyword);
	}

	$allKeywords = $oSubmissionModel->getKeywords ()->toArray();
	$oUsers = array();

	foreach ($keywords as $keyword) {
		if(!empty ($keyword) && isSet ($allKeywords[$keyword])) {
			foreach ($allKeywords[$keyword]['users'] as $k => $user) {
				$oUsers[$user] = $oUserModel->get ($user);
			}
		}
	}
	$oUsers = \array_values ($oUsers);
} else {
	$oUsers = $oUserModel->getAll (null, function ($user) {
		return $user->countSubmission;
	});
}

// Format the submission for output
$output = array();
foreach ($oUsers as $oUser) {
	$temp = $oSubmissionModel->get ($oUser->username, false);

	if (isSet ($temp->text)) {
		$userData =  $oUserModel->get ($oUser->username);
		
		$temp->text = $oUserModel->makeSubsts ($temp->text, $oUser->username);
	
		$textMd = $temp->text;
		$textHtml = !empty ($textMd) ? $oSubmissionModel->markdownToHtml ($textMd) : '<em>No text submitted.</em>';

		$refMd = \trim ($temp->references);
		$refHtml = !empty ($textMd) && !empty ($refMd) ?  '<h1>References</h1>' . $oSubmissionModel->markdownToHtml ($refMd) : '';
		
		$pubMd = \trim ($temp->publications);
		$pubHtml = !empty ($pubMd) ? '<h1>Publications in the Last Year</h1>' . $oSubmissionModel->markdownToHtml ($pubMd) : '';

		$temp->html = $textHtml . $refHtml . $pubHtml;
		$temp->fundingStatement = $oUserModel->getFunding ($oUser->username);

		$output[] = \array_merge ($temp->toArray (), $userData->toArray ());
	}
}

print \json_encode ($output);
exit;