<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Redirects for the submissions

$rh = \CDT\RH::i();
$oUserModel = $rh->cdt_user_model;

$redirectTo = \substr ($page, 8);

if (!(empty ($redirectTo) && ($ouser = $oUserModel->get ($redirectTo)) !== false)) {
	header ('Location: ' . URI_ROOT .'/#read=' . $redirectTo);
	exit;
}