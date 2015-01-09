<?php

$rh = \CDT\RH::i();

$oData = $rh->cdt_data;
$oInput = $rh->cdt_input;
$oUser = $rh->cdt_user;

$cohort = $oInput->get ('cohort');
if (!is_numeric ($cohort)) {
	$cohort = null;
}

$users = $oUser->getAll (null, function ($user) use ($cohort) {
	return $user['enabled'] == '1' && (is_null ($cohort) ? true : $user['cohort'] == $cohort);
});

die (\json_encode ($users));