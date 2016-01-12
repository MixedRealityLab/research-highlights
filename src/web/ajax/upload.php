<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of users who have submitted

\header('Vary: Accept');

if (isset($_SERVER['HTTP_ACCEPT']) && (\strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
    \header('Content-type: application/json');
} else {
    \header('Content-type: text/plain');
}

try {
    if ($names = I::RH_Model_Input()->upload('files', DIR_IMG)) {
        \array_walk($names, function (&$value, $key) {
            $value = URI_IMG .'/'. $value;

        });
        print \json_encode($names);
    }
} catch (\RH\Error $e) {
    print $e->toJson();
}
