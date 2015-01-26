<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of keywords

$rh = \CDT\RH::i();
$oPageInput = $rh->cdt_page_input;
$oUserController = $rh->cdt_user_controller;
$oSubmissionController = $rh->cdt_submission_controller;

// for just one user?
if (isSet ($oPageInput->user)) {
	$user = $oPageInput->user;
	print \CDT\Submission\Keywords::mergeJson ($oSubmissionController->getKeywords ($user)->toArray());
	exit;
}

// is there a saved copy of all keywords?
$file = DIR_DAT . '/keywords.txt';
if (\is_file ($file) && \filemtime ($file) + KEY_CACHE > \date ('U')) {
	print @\file_get_contents ($file);
}

// generate list of keywords for everyone
$keywordsList = $oSubmissionController->getKeywords ()->toArray();
\ksort ($keywordsList);
$keywords = \CDT\Submission\Keywords::mergeJson (\array_values ($keywordsList));
@\file_put_contents ($file, $keywords);
@\chmod ($file, 0777);
print $keywords;