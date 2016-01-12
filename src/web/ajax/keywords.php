<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of keywords

\header('Content-type: application/json');

try {
    $mInput = I::RH_Model_Input();
    $cSubmission = I::RH_Submission();

    // for just one user?
    if (isset($mInput->user)) {
        $cUser = I::RH_User();
        $mUser = $cUser->get($mInput->user);
        $mKeyword = $cSubmission->get($mUser, false)->getKeywords();
        print $mKeyword->toArrayJson();
        exit;
    }

    print \RH\Keywords::get()->getKeys()->toArrayJson();
} catch (\RH\Error $e) {
    print $e->toJson();
}
