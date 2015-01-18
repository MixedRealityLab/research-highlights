<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of eywords

$rh = \CDT\RH::i();
$oInputModel = $rh->cdt_input_model;
$oUserModel = $rh->cdt_user_model;
$oSubmissionModel = $rh->cdt_submission_model;

// For a user, or all users?
$user = null;
if(!is_null ($oInputModel->get('user'))) {
	$user = $oInputModel->get ('user');
	print \CDT\Submission\Keywords::mergeJson ($oSubmissionModel->getKeywords ($user)->toArray());
} else {
	// is there a saved copy of all keywords?
	$file = DIR_DAT . '/keywords.txt';
	if (\is_file ($file) && \filemtime ($file) + KEY_CACHE > \date ('U')) {
		print @\file_get_contents ($file);
	} else {
		$keywordsList = $oSubmissionModel->getKeywords ()->toArray();
		\ksort ($keywordsList);
		$keywords = \CDT\Submission\Keywords::mergeJson (\array_values ($keywordsList));
		@\file_put_contents ($file, $keywords);
		@\chmod ($file, 0777);
		print $keywords;
	}
}