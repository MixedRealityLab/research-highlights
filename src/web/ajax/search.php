<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Perform a search

$rh = \CDT\RH::i();
$oInputModel = $rh->cdt_input_model;
$oUserModel = $rh->cdt_user_model;
$oSubmissionModel = $rh->cdt_submission_model;

// if no query, no results...
if (\is_null ($oInputModel->get ('q'))) {
	print '[]';
	exit;
}

// is there a cached db?
$file = DIR_DAT . '/search-keywords.txt';
if (true || !\is_file ($file) || \filemtime ($file) + KEY_CACHE > \date ('U')) {
	// Weights
	$weights = array();
	$weights['author']				= 40;
	$weights['keyword'] 			= 20;
	$weights['title']				= 10;
	$weights['text_h1']				= 9; // text H1
	$weights['text_h2']				= 8; // text H2
	$weights['text_h3']				= 7; // text H3
	$weights['text_h4']				= 6; // text H4
	$weights['text_strong']			= 5; // text bold
	$weights['text_em']				= 3; // text italics
	$weights['text_blockquote']		= 3; // text quotes
	$weights['text']				= 2; // text

	// Usage factors; how much the weight changes by the more its used
	$useFactors = array();
	$useFactors['author']			= 1;
	$useFactors['keyword'] 			= .95;
	$useFactors['title'] 			= .85;
	$useFactors['text_h1'] 			= .90;
	$useFactors['text_h2'] 			= .91;
	$useFactors['text_h3'] 			= .92;
	$useFactors['text_h4'] 			= .93;
	$useFactors['text_strong'] 		= .94;
	$useFactors['text_em'] 			= .94;
	$useFactors['text_blockquote'] 	= .95;
	$useFactors['text'] 			= .96;

	// Weighted factors and all keywords
	$globalWeights = array();
	$searchKeywords = array();

	// get weight
	function addKeywords ($keywords, $weight, $factor, $user) {
		$keywordsA = \explode ("\n", \trim ($keywords));
			foreach ($keywordsA as $keywordB) {
				$keywordC = \explode (' ', \trim ($keywordB));
				foreach ($keywordC as $keyword) {
					addKeyword($keyword, $weight, $factor, $user);
				}
		}
	}

	function addKeyword ($keyword, $weight, $factor, $user) {
		global $globalWeights, $searchKeywords;

		$keyword = \strtolower ($keyword);

		if (isSet ($globalWeights[$keyword])) {
			if (!\in_array ($user, $searchKeywords[$keyword]['users'])) {
				$searchKeywords[$keyword]['weight'] = $globalWeights[$keyword] * $weight;
				$searchKeywords[$keyword]['users'][] = $user;
				$globalWeights[$keyword] *= $factor;
			}
		} else {
			$searchKeywords[$keyword] = array ('weight' => $weight, 'users' => array ($user));
			$globalWeights[$keyword] = $factor;
		}
	}

	function getTags($tag, $text) {
		$matches = array();
		preg_match_all ("/<$tag>(.*?)<\/$tag>/", $text, $matches, PREG_PATTERN_ORDER);
    	return $matches[1];
	}

	// load all submission data
	$oUsers = $oUserModel->getAll (null, function ($user) {
		return $user->countSubmission;
	});


	foreach ($oUsers as $oUser) {
		$data = $oSubmissionModel->get ($oUser->username, false);

		if (!isSet ($data->text)) {
			continue;
		}

		addKeywords ($oUser->firstName, $weights['author'], $useFactors['author'], $oUser->username);
		addKeywords ($oUser->surname, $weights['author'], $useFactors['author'], $oUser->username);

		addKeywords ($data->title, $weights['title'], $useFactors['title'], $oUser->username);

		$keywords = \explode (',', $data->keywords);
		foreach ($keywords as $keyword) {
			addKeywords ($words, $weights['keyword'], $useFactors['keyword'], $oUser->username);
		}

		$text = $oUserModel->makeSubsts ($data->text, $oUser->username);
		$text = $oSubmissionModel->markdownToHtml ($text);

		$tags = array('h1', 'h2', 'h3', 'h4', 'strong', 'em', 'blockquote');
		foreach ($tags as $tag) {
			$results = getTags ($tag, $text);
			foreach ($results as $result) {
				addKeywords (\trim (\strip_tags ($text)), $weights['text_' . $tag], $useFactors['text_' . $tag], $oUser->username);
			}
		}
	}

	@\file_put_contents ($file, \serialize ($searchKeywords));
}


// keyword database
$db = \unserialize (\file_get_contents ($file));
$kDb = \array_keys ($db);

// load results                      
$results = array();
$query = $oInputModel->get ('q');
$qWords = \explode (' ', $query);
foreach ($qWords as $qWord) {
	$qWordL = \trim (\strtolower ($qWord));
	if (!empty ($qWordL)) {
		foreach ($db as $key => $row) {
			if (\strpos ($key, $qWord) !== false) {
				$row['qWord'] = $qWord;
				$row['key'] = $key;
				$results[] = $row;
			}
		}
	}
}

// Get user weights
$userWeights = array();
foreach ($results as $result) {
	foreach ($result['users'] as $user) {
		if (!isSet ($userWeights[$user])) {
			$userWeights[$user] = $result['weight'];
		} else {
			$userWeights[$user] += $result['weight'];
		}
	}
}

\arsort ($userWeights, SORT_NUMERIC);

// Collect the relevant submissions and return
$output = array();
foreach ($userWeights as $username=>$weight) {
	$oUser = $oUserModel->get ($username);
	$temp = $oSubmissionModel->get ($username, false);

	if (isSet ($temp->text)) {
		$output[] = \array_merge ($temp->toArray (), $oUser->toArray (), array('weight' => $weight));
	}
}

print \json_encode ($output);