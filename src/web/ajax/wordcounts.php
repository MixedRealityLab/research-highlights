<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the wordcount

\header('Content-type: application/json');

\define('NO_CACHE', true);

try {
    $cUser = I::RH_User();
    print $cUser->getAllWordCounts()->toArrayJson();
} catch (\RH\Error $e) {
    print $e->toJson();
}