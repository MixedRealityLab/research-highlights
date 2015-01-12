<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();
$oPageTemplate = $rh->cdt_page_template;

$oPageTemplate->add ('css', 'app/css/hitlist.css');

$oPageTemplate->add ('javascript', 'sys/js/jquery.ba-hashchange.js');
$oPageTemplate->add ('javascript', 'app/js/main.js');
$oPageTemplate->add ('javascript', 'app/js/hitlist.js');

$oPageTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container hitlist"></div>');

print $oPageTemplate->load ('2015');