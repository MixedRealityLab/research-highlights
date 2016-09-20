<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the wordcounts for each cohort.

use \RH\Validator as V;

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mWordCounts = new \RH\Model\WordCounts();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    foreach ($mInput->wordcount[0] as $key => $cohort) {
        $mWordCount = new \RH\Model\WordCount();
        $cValidator = new \RH\Validator($mInput, $mWordCount);

        $identKey = 'wordcount[0]['. $key .']';

        $data = [
            ['Cohort', 'wordcount[0]['. $key .']', 'cohort', true, V::NON_EMPTY|V::T_INT, null],
            ['Word Count', 'wordcount[1]['. $key .']', 'wordCount', true, V::NON_EMPTY|V::T_INT, null]
        ];
        
        if ($cValidator->testAndSetAll($data, true, $identKey)) {
            $mWordCounts->__set($mWordCount->cohort, $mWordCount);
        }
    }

    print \json_encode(array ('success' => $cUser->updateWordCounts($mWordCounts) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}
