<?php

$rh = \CDT\RH::i();
$oData = $rh->cdt_data;
$oInput = $rh->cdt_input;
$oUser = $rh->cdt_user;

$cohort = $oInput->get ('cohort');
if (!\is_numeric ($cohort)) {
	$cohort = null;
}

$submitted = $oInput->get ('submitted');
if ($submitted === '1') {
	$submitted = true;
} else if ($submitted === '0') {
	$submitted = false;
} else {
	$submitted = null;
}

$users = $oUser->getAll (null, function ($user) use ($oData, $cohort, $submitted) {
	$userData = $oData->get ($user['username'], false);
	return $user['enabled'] == '1' && (\is_null ($cohort) ? true : $user['cohort'] == $cohort) && (\is_null ($submitted) ? true : isSet ($userData['text']) === $submitted && $user['countSubmission']);
});

exit (\json_encode ($users));