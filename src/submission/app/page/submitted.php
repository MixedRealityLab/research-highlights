<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();
$oTemplate = $rh->cdt_template;

$oTemplate->add ('css', 'app/css/submitted.css');

$oTemplate->add ('javascript', 'sys/js/jquery.ba-hashchange.js');
$oTemplate->add ('javascript', 'app/js/main.js');
$oTemplate->add ('javascript', 'app/js/submitted.js');

$oTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container submitted"></div>');

print $oTemplate->load ('2015');