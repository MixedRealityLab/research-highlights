<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch a list of cohorts

$rh = \CDT\RH::i();
$oUserModel = $rh->cdt_user_model;

print \json_encode ($oUserModel->getCohorts ());