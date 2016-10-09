<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the deadlines for each cohort.

use \RH\Model\InputValidator as V;

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mDeadlines = new \RH\Model\Deadlines();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    foreach ($mInput->deadline[0] as $key => $cohort) {
        $mDeadline = new \RH\Model\Deadline();
        $oValidator = $mInput->getValidator($mDeadline);

        $identKey = 'deadline[0]['. $key .']';

        $data = [
            ['Cohort', 'deadline[0]['. $key .']', 'cohort', true, V::NON_EMPTY|V::T_INT, null],
            ['Deadline', 'deadline[1]['. $key .']', 'deadline', true, V::NON_EMPTY, null]
        ];
        
        if ($oValidator->testAndSetAll($data, true, $identKey)) {
            $mDeadlines->__set($mDeadline->cohort, $mDeadline);
        }
    }
    
    print \json_encode(array ('success' => $cUser->updateDeadlines($mDeadlines) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}
