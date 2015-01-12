<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();
$oPageTemplate = $rh->cdt_page_template;

$oPageTemplate->add ('css', URI_WEB . '/css/submitted.css');

$oPageTemplate->add ('javascript', URI_SYS . '/js/jquery.ba-hashchange.js');
$oPageTemplate->add ('javascript', URI_WEB . '/js/main.js');
$oPageTemplate->add ('javascript', URI_WEB . '/js/submitted.js');

$oPageTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container submitted"></div>');

print $oPageTemplate->load ('2015');