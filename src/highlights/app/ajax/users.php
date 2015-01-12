<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();

$oSubmissionModel = $rh->cdt_submission_model;
$oUserModel = $rh->cdt_user_model;
$oInputModel = $rh->cdt_input_model;

$oUsers = $oUserModel->getAll ();

function cmp_users ($a, $b) {
	if ($a['cohort'] < $b['cohort']) {
		return -1;
	} else if ($a['cohort'] > $b['cohort']) {
		return 1;
	} else {
		return strcmp ($a['name'], $b['name']);
	}
}

usort ($oUsers, 'cmp_users');

$output = array ();
$cohort = $oInputModel->get ('cohort');
if (!is_numeric ($cohort)) {
	$cohort = null;
}

foreach ($oUsers as $oUser) {
	if ((!is_null ($cohort) && $user->cohort == $cohort) || is_null ($cohort)) {
		$output[] = $oUser;
	}
}

exit (json_encode ($output));