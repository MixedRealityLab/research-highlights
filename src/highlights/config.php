<?php

error_reporting (E_ALL); ini_set ('display_errors', '1');

define ('DIR_AJX', DIR . '/app/ajax');
define ('DIR_PAG', DIR . '/app/page');
define ('DIR_TPL', DIR . '/app/tpl');
define ('DIR_DAT', dirname (DIR) . '/dat');
define ('DIR_LIB', DIR .  '/sys/lib');
define ('DIR_USR', DIR . '/usr');

define ('DOMAIN', 'https://www.porcheron.uk');
define ('PATH', '/cdt/rh');

define ('URI_HOME', DOMAIN . PATH);
define ('URI_DATA', dirname (URI_HOME) . '/dat');