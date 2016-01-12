<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Redirects for the submissions

$cUser = \I::RH_User();

$redirectTo = \substr($page, 8);

if (!(empty($redirectTo) && ($ouser = $cUser->get($redirectTo)) !== false)) {
    header('Location: ' . URI_ROOT .'/#read=' . $redirectTo);
    exit;
}
