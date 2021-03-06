<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$cTemplate = I::RH_Template();

$cTemplate->add('css', URI_WEB . '/css/print' . EXT_CSS);

$cTemplate->add('javascript', URI_SYS . '/js/jquery.ba-hashchange' . EXT_JS);
$cTemplate->add('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add('javascript', URI_WEB . '/js/print' . EXT_JS);

$cTemplate->set('body', '<div class="loading">Loading, please wait...</div><div class="container read"></div>');

print $cTemplate->load('2015');
