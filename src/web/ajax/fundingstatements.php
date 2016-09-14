<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Get the funding statements

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    print $cUser->getAllFunding()->toArrayJson();
} catch (\RH\Error $e) {
    print $e->toJson();
}