<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Perform a search

\header('Content-type: application/json');

$mInput = I::RH_Model_Input();
$cSearch = I::RH_Search();

// if no query, no results...
if (!isset($mInput->q)) {
    print '[]';
    exit;
}

$query = $mInput->q;
print $cSearch->search($query)->toArrayJson();
