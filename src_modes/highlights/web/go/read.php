<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Redirects for the submissions

$oUser = \I::rh_user_controller ();

$redirectTo = \substr ($page, 8);

if (!(empty ($redirectTo) && ($ouser = $oUser->get ($redirectTo)) !== false)) {
	header ('Location: ' . URI_ROOT .'/#read=' . $redirectTo);
	exit;
}