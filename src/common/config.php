<?php

define ('DIR_AJX', DIR . '/app/ajax');
define ('DIR_PAG', DIR . '/app/page');
define ('DIR_TPL', DIR . '/app/tpl');
define ('DIR_DAT', DIR . '/dat');
define ('DIR_LIB', DIR .  '/sys/lib');
define ('DIR_USR', DIR . '/usr');

define ('DOMAIN', 'http://www.example.ac.uk');
define ('PATH', '/highlights');

define ('URI_HOME', DOMAIN . PATH);
define ('URI_DATA', URI_HOME . '/dat');

define ('PAG_HOME', 'index');
define ('SYS_HTAC', false);

define ('DEF_DAT', 'default.txt');

define ('MAIL_HOST', 'ssl://smtp.nottingham.ac.uk');
define ('MAIL_PORT', 465);
define ('MAIL_AUTH', false);
define ('MAIL_USER', '');
define ('MAIL_PASS', '');

date_default_timezone_set ('Europe/London');