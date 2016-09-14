<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of students

\header('Content-type: application/json');

try {
    $mInput = I::RH_Model_Input();
    $cUser = I::RH_User();

    print $cUser->getAll(null, function ($mUser) {
        return !$mUser->admin;
    })->toArrayJson();
} catch (\RH\Error $e) {
    print $e->toJson();
}
