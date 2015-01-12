
<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$oPageTemplate = \CDT\RH::i()->cdt_page_template;

$oPageTemplate->add ('css', 'app/css/read.css');

$oPageTemplate->add ('javascript', 'sys/js/jquery.ba-hashchange.js');
$oPageTemplate->add ('javascript', 'app/js/main.js');
$oPageTemplate->add ('javascript', 'app/js/read.js');

$oPageTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container read"></div>');

print $oPageTemplate->load ('2015');