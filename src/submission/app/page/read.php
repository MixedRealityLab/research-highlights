<?php

$oTemplate = \CDT\Submission::template();

$oTemplate->add ('css', 'app/css/read.css');

$oTemplate->add ('javascript', 'sys/js/jquery.ba-hashchange.js');
$oTemplate->add ('javascript', 'app/js/read.js');

$oTemplate->set ('body', '<div class="loading">Loading, please wait...</div><div class="container"></div>');

print $oTemplate->load ('2015');