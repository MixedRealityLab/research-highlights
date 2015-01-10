<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();

$oUser = $rh->cdt_user;
if (!$oUser->login ()) {
	exit ('-1');
}

$oData = $rh->cdt_data;
$oInput = $rh->cdt_input;

if (is_null ($oInput->get('saveAs'))) {
	exit('-3');
}

if($oInput->get('username') !== $oInput->get ('saveAs') && !$oUser->login (true)) {
	exit('-5');
}

$user = $oUser->get ($oInput->get ('saveAs'));
$cohortDir = DIR_DAT . '/' . $oInput->get ('cohort');
$dir = DIR_DAT . '/' . $oInput->get ('cohort') . '/' . $oInput->get ('saveAs')  . '/' . date ('U') .'/';

try {
	if (is_null ($oInput->get ('cohort')) || is_null ($oInput->get ('title')) || is_null ($oInput->get ('keywords')) || is_null ($oInput->get ('text'))) {
		throw new \CDT\InvalidInputException ('Missing inputs');
	}

	if ($oInput->get ('cohort') !== $user['cohort'] || !is_numeric ($oInput->get ('cohort')) || !is_dir ($cohortDir)) {
		throw new \CDT\InvalidInputException ('Invalid cohort!');
	}

	$save = $oInput->getAll (\CDT\Input::POST);
	
	// place to store the data
	if (strpos ($dir, '..') !== false) {
		throw new \CDT\SystemException ('Could not identify directory to save input to');
	}

	if (@mkdir ($dir, 0777, true) === false) {
		throw new \CDT\SystemException ('Could not create directory to save input to');
	}

	$html = $oData->markdownToHtml ($save['text']);

	$images = array();
	preg_match_all('/(<img).*(src\s*=\s*("|\')([a-zA-Z0-9\.;:\/\?&=\-_|\r|\n]{1,})\3)/isxmU', $html, $images, PREG_PATTERN_ORDER);

	$id = 0;
	foreach ($images[4] as $url) {
		$img = @file_get_contents ($url);
		if ($img === false) {
			throw new \CDT\SystemException ('Could not fetch the image at ' . $url);
		}

		$path_parts = \pathinfo ($url);
		$ext = $path_parts['extension'];
		if (\strpos ($ext, '?') !== false) {
			$ext = \substr ($ext, 0, \strpos ($ext, '?'));	
		}

		$filename = 'img-' . $id++ . '.' . $ext;

		if (!@file_put_contents ($dir . $filename, $img)) {
			throw new \CDT\SystemException ('Could not save the image at ' . $url . ' to the system');
		}

		$save['text'] = \str_replace ($url, '<img-dir>' . $filename, $save['text']);
	}


	$save['website'] = !is_null ($save['website']) && $save['website'] != 'http://' ? trim ($save['website']) : '';
	$save['twitter'] = strlen ($save['twitter']) > 0 && $save['twitter'][0] != '@' ? '@' . $save['twitter'] : $save['twitter'];

	foreach ($oData->getDefaultData () as $key => $value) {
		if (!isSet ($save[$key]) || is_null ($save[$key])) {
			$save[$key] = '';
		}

		if (@file_put_contents ($dir . $key .'.txt', $save[$key]) === false) {
			throw new \CDT\SystemException ('Could not save ' . $key . ' to the system');
		}
	}

	exit ('1');
} catch (\Exception $e) {
	if (is_dir ($dir)) {
		if ($dh = opendir ($dir)) {
			$versions = array ();
	        while (($file = readdir ($dh)) !== false) {
	        	if ($file != '.' && $file != '..' && is_dir ($dir . $file)) {
	        		@rmdir ($dir . $file);
	        	} else {
	        		@unlink ($dir . $file);
	        	}
	        }
	        closedir ($dh);
	        @rmdir ($dir);
	    }
	}

	exit ($e->getMessage());
}