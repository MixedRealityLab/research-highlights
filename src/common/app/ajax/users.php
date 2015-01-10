<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users (either all, a cohort, or (not) submitted)

$rh = \CDT\RH::i();
$oInput = $rh->cdt_input;
$oUser = $rh->cdt_user;

// Fetch a specific cohort?
$cohort = $oInput->get ('cohort');
if (!\is_numeric ($cohort)) {
	$cohort = null;
}

// Fetch those who have submitted, or not?
$submitted = $oInput->get ('submitted');
if ($submitted === '1') {
	$submitted = true;
} else if ($submitted === '0') {
	$submitted = false;
} else {
	$submitted = null;
}

// Filter the user list
$res = $oUser->getAll (null, function ($user) use ($rh, $cohort, $submitted) {
	$oData = $rh->cdt_data;
	$userData = $oData->get ($user['username'], false);

	$isCohort = is_null ($cohort) ? true : $user['cohort'] === $cohort;
	$isSubmitted = \is_null ($submitted)
		? true 
		: isSet ($userData['text']) === $submitted && $user['countSubmission'];

	return !$user['enabled'] && $isCohort && $isSubmitted;
});-

print \json_encode ($res);
exit;