<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the wordcounts for each cohort.

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mWordCounts = new \RH\Model\WordCounts();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    foreach($mInput->wordcount[0] as $key => $cohort) {
    	$wordcount = $mInput->wordcount[1][$key];
    	if (!empty($cohort) && !empty($wordcount)) {
    		if (!is_numeric($cohort)) {
    			throw new \RH\Error\InvalidInput('Cohort value "'. $cohort .'" is not numeric');
    		} else if (!is_numeric($wordcount)) {
    			throw new \RH\Error\InvalidInput('Word count value "'. $wordcount .'" is not numeric');
            } else {
                $mWordCounts->__set($id, array ('cohort' => $cohort, 'wordCount' => $wordcount));
            }
    	}
    }

    print \json_encode(array ('success' => $cUser->updateWordCounts($mWordCounts) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}