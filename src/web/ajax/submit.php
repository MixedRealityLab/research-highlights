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
$oUserModel = $rh->cdt_user_model;
if (!$oUserModel->login ()) {
	print '-1';
	exit;
}

$oSubmissionModel = $rh->cdt_submission_model;
$oInputModel = $rh->cdt_input_model;

if (\is_null ($oInputModel->get('saveAs'))) {
	print '-3';
	exit;
}

if ($oInputModel->get ('username') !== $oInputModel->get ('saveAs')
	&& !$oUserModel->login (true)) {
	print '-5';
	exit;
}

// Go ahead and save the submission!
$oUser = $oUserModel->get ($oInputModel->get ('saveAs'));
$cohortDir = DIR_DAT . '/' . $oInputModel->get ('cohort');
$dir = DIR_DAT . '/' . $oInputModel->get ('cohort') . '/';
$dir .= $oInputModel->get ('saveAs')  . '/' . date ('U') .'/';

try {
	if (\is_null ($oInputModel->get ('cohort'))
		|| \is_null ($oInputModel->get ('title'))
		|| \is_null ($oInputModel->get ('keywords'))
		|| \is_null ($oInputModel->get ('text'))) {
		throw new \CDT\Error\InvalidInput ('Missing inputs');
	}

	if ($oInputModel->get ('cohort') !== $oUser->cohort
		|| !is_numeric ($oInputModel->get ('cohort')) || !is_dir ($cohortDir)) {
		throw new \CDT\Error\InvalidInput ('Invalid cohort!');
	}

	$save = $oInputModel->getAll (\CDT\Input\Model::POST);
	
	// place to store the data
	if (strpos ($dir, '..') !== false) {
		throw new \CDT\Error\System ('Could not identify directory to save input to');
	}

	if (@mkdir ($dir, 0777, true) === false) {
		throw new \CDT\Error\System ('Could not create directory to save input to');
	}

	$html = $oSubmissionModel->markdownToHtml ($save->text);

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

		if (!@\file_put_contents ($dir . $filename, $img)) {
			throw new \CDT\Error\System ('Could not save the image at ' . $url . ' to the system');
		}

		$save->text = \str_replace ($url, '<imgDir>' . $filename, $save->text);
	}

	$save->keywords = \strtolower ($save->keywords);

	$save->website = !\is_null ($save->website) && $save->website != 'http://' ? \trim ($save->website) : '';
	$save->twitter = \strlen ($save->twitter) > 0 && $save->twitter[0] != '@' ? '@' . $save->twitter : $save->twitter;

	// fix this
	foreach ($oSubmissionModel->getDefaultData ()->toArray () as $key => $value) {
		if (!isSet ($save->$key)) {
			$save->$key = '';
		}

		if (@\file_put_contents ($dir . $key .'.txt', $save->$key) === false) {
			throw new \CDT\Error\System ('Could not save ' . $key . ' to the system');
		}
	}

	print '1';
	exit;
} catch (\Exception $e) {
	// Roll back saved changes
	if (\is_dir ($dir)) {
		if ($dh = \opendir ($dir)) {
			$versions = array ();
			while (($file = \readdir ($dh)) !== false) {
				if ($file != '.' && $file != '..' && \is_dir ($dir . $file)) {
					@\rmdir ($dir . $file);
				} else {
					@\unlink ($dir . $file);
				}
			}
			\closedir ($dh);
			@\rmdir ($dir);
		}
	}

	print $e->getMessage();
	exit;
}