<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();

$oData = $rh->cdt_data;
$oUser = $rh->cdt_user;
$oInput = $rh->cdt_input;

$users = $oUser->getAll ();

function cmp_users ($a, $b) {
	if ($a['cohort'] < $b['cohort']) {
		return -1;
	} else if ($a['cohort'] > $b['cohort']) {
		return 1;
	} else {
		return strcmp ($a['name'], $b['name']);
	}
}

usort ($users, 'cmp_users');

$output = array ();
$cohort = $oInput->get ('cohort');
if (!is_numeric ($cohort)) {
	$cohort = null;
}

foreach ($users as $user) {
	if ((!is_null ($cohort) && $user['cohort'] == $cohort) || is_null ($cohort)) {
		$output[] = $user;
	}
}

exit (json_encode ($output));