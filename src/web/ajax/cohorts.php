<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of cohorts

\header ('Content-type: application/json');

try {
	print I::RH_User ()
		->getCohorts ()
		->toArrayJson();
} catch (\RH\Error $e) {
	print $e->toJson ();
}

