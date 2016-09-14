<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the deadlines for each cohort.

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mDeadlines = new \RH\Model\Deadlines();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    foreach($mInput->deadline[0] as $key => $cohort) {
    	$deadline = $mInput->deadline[1][$key];
    	if (!empty($cohort) && !empty($deadline)) {
    		if (!is_numeric($cohort)) {
    			throw new \RH\Error\InvalidInput('Cohort value "'. $cohort .'" is not numeric');
	    	} else {
                $mDeadlines->__set($cohort, array ('cohort' => $cohort, 'deadline' => $deadline));
	    	}
    	}
    }

    print \json_encode(array ('success' => $cUser->updateDeadlines($mDeadlines) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}