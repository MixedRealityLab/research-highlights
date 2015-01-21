<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Save a user's submission
//  1 : Success!
// -1 : Not logged in
// -3 : No details on who to save submission as
// -5 : Attempting to masquerade when not admin

$rh = \CDT\RH::i();
$oUserController = $rh->cdt_user_controller;
if (!$oUserController->login ()) {
	print '-1';
	exit;
}

$oSubmissionController = $rh->cdt_submission_controller;
$oPageInput = $rh->cdt_page_input;

if (\is_null ($oPageInput->saveAs)) {
	print '-3';
	exit;
}

if ($oPageInput->username !== $oPageInput->saveAs
	&& !$oUserController->login (true)) {
	print '-5';
	exit;
}

// Go ahead and save the submission!

try {
	if (!isSet ($oPageInput->cohort) && !isSet ($oPageInput->title)
		&& !isSet ($oPageInput->keywords) && !isSet ($oPageInput->text)) {
		throw new \CDT\Error\InvalidInput ('Missing inputs');
	}

	$oUser = $oUserController->get ($oPageInput->saveAs);
	$cohortDir = DIR_DAT . '/' . $oPageInput->cohort;
	if ($oPageInput->cohort !== $oUser->cohort
		|| !is_numeric ($oPageInput->cohort) || !is_dir ($cohortDir)) {
		throw new \CDT\Error\InvalidInput ('Invalid cohort!');
	}

	$oSubmission = new \CDT\Submission\Submission ($oPageInput);

	$html = $oSubmissionController->markdownToHtml ($oSubmission->text);

	$images = array();
	\preg_match_all ('/(<img).*(src\s*=\s*("|\')([a-zA-Z0-9\.;:\/\?&=\-_|\r|\n]{1,})\3)/isxmU', $html, $images, PREG_PATTERN_ORDER);

	$id = 0;
	foreach ($images[4] as $url) {
		$img = @\file_get_contents ($url);
		if ($img === false) {
			throw new \CDT\Error\System ('Could not fetch the image at ' . $url);
		}

		$path_parts = \pathinfo ($url);
		$ext = $path_parts['extension'];
		if (\strpos ($ext, '?') !== false) {
			$ext = \substr ($ext, 0, \strpos ($ext, '?'));	
		}

		$filename = 'img-' . $id++ . '.' . $ext;

		$oSubmission->addImage ($filename, $img);
		$oSubmission->text = \str_replace ($url, '<imgDir>' . $filename, $oSubmission->text);
	}

	$oSubmission->keywords = \strtolower ($oSubmission->keywords);

	$oSubmission->website = !\is_null ($oSubmission->website) && $oSubmission->website != 'http://' ? \trim ($oSubmission->website) : '';
	$oSubmission->twitter = \strlen ($oSubmission->twitter) > 0 && $oSubmission->twitter[0] != '@' ? '@' . $oSubmission->twitter : $oSubmission->twitter;

	$oSubmission->save ();

	print '1';
	exit;
} catch (\Exception $e) {
	print $e->getMessage();
	exit;
}