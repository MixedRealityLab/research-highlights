<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$oPageTemplate = \I::rh_page_template ();

$oPageTemplate->add ('css', URI_WEB . '/css/read' . EXT_CSS);

$oPageTemplate->add ('javascript', URI_SYS . '/js/jquery.ba-hashchange' . EXT_JS);
$oPageTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$oPageTemplate->add ('javascript', URI_WEB . '/js/read' . EXT_JS);

$oPageTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container read"></div>');

print $oPageTemplate->load ('2015');