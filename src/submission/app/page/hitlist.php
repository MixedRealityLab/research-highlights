<?php

$rh = \CDT\RH::i();
$oTemplate = $rh->cdt_template;

$oTemplate->add ('css', 'app/css/hitlist.css');

$oTemplate->add ('javascript', 'sys/js/jquery.ba-hashchange.js');
$oTemplate->add ('javascript', 'app/js/main.js');
$oTemplate->add ('javascript', 'app/js/hitlist.js');

$oTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container hitlist"></div>');

print $oTemplate->load ('2015');