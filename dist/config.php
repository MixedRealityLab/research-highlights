<?php

error_reporting (E_ALL); ini_set ('display_errors', '1');

define ('DIR_AJX', DIR . '/app/ajax');
define ('DIR_PAG', DIR . '/app/page');
define ('DIR_TPL', DIR . '/app/tpl');
define ('DIR_DAT', dirname (DIR) . '/dat');
define ('DIR_LIB', DIR .  '/sys/lib');
define ('DIR_USR', DIR . '/usr');

define ('DOMAIN', 'http://cdt.horizon.ac.uk');
define ('PATH', '/rh-submit');

define ('URI_HOME', DOMAIN . PATH);
define ('URI_DATA', dirname (URI_HOME) . '/dat');

define ('PAG_HOME', 'index');
define ('SYS_HTAC', false);

date_default_timezone_set ('Europe/London');