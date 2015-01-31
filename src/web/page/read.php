<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$oTemplate = I::RH_Template ();

$oTemplate->add ('css', URI_WEB . '/css/read' . EXT_CSS);

$oTemplate->add ('javascript', URI_SYS . '/js/jquery.ba-hashchange' . EXT_JS);
$oTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$oTemplate->add ('javascript', URI_WEB . '/js/read' . EXT_JS);

$oTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container read"></div>');

print $oTemplate->load ('2015');