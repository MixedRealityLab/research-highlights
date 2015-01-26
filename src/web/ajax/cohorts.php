<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of cohorts

print I::RH_User_Controller ()
	->getCohorts ()
	->toArrayJson();

