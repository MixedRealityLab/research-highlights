<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();
$oPageTemplate = $rh->cdt_page_template;

$oPageTemplate->add ('css', URI_WEB . '/css/read.min.css');

$oPageTemplate->add ('javascript', URI_SYS . '/js/jquery.ba-hashchange.min.js');
$oPageTemplate->add ('javascript', URI_WEB . '/js/main.min.js');
$oPageTemplate->add ('javascript', URI_WEB . '/js/read.min.js');

$oPageTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container read"></div>');

print $oPageTemplate->load ('2015');