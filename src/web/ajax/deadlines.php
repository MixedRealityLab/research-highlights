<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Get the deadlines for each cohort.

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    print $cUser->getAllDeadlines()->toArrayJson();
} catch (\RH\Error $e) {
    print $e->toJson();
}