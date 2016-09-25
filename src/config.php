<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

\define('SITE_NAME', 'Horizon CDT Research Highlights');
\define('SITE_YEAR', '2016');

\define('DIR_WAJ', DIR . '/web/ajax');
\define('DIR_WCS', DIR . '/web/css');
\define('DIR_WJS', DIR . '/web/js');
\define('DIR_WIM', DIR . '/web/img');
\define('DIR_WPG', DIR . '/web/page');
\define('DIR_WXM', DIR . '/web/xml');
\define('DIR_WGO', DIR . '/web/go');
\define('DIR_WTP', DIR . '/web/tpl');

\define('DIR_DAT', DIR . '/submissions-' . SITE_YEAR);
\define('DIR_CAC', DIR . '/submissions-' . SITE_YEAR .'/cache');
\define('DIR_IMG', DIR . '/submissions-' . SITE_YEAR .'/images');

\define('DIR_SLB', DIR .  '/sys/lib');
\define('DIR_SCS', DIR . '/sys/css');
\define('DIR_SJS', DIR . '/sys/js');
\define('DIR_CRN', DIR . '/sys/cron');

\define('DIR_USR', DIR . '/usr');

\define('EMAIL', 'emma.juggins@nottingham.ac.uk');

\define('DOMAIN', '@@@DOMAIN@@@');
\define('PATH', '@@@PATH@@@');

\define('PAG_HOME', 'read');
\define('SYS_HTAC', true);

\define('EXT_CSS', '.css');
\define('EXT_JS', '.js');

\define('URI_HOME', '@@@DOMAIN@@@@@@PATH@@@');
\define('URI_ROOT', '@@@URI_ROOT@@@');
\define('URI_NICE', '@@@URI_NICE@@@');
\define('URI_WEB', URI_ROOT . '/web');
\define('URI_SYS', URI_ROOT . '/sys');
\define('URI_DATA', URI_ROOT . '/submissions-' . SITE_YEAR);
\define('URI_IMG', URI_ROOT . '/submissions-' . SITE_YEAR .'/images');

\define('TITLE', '@@@SITE_NAME@@@ @@@SITE_YEAR@@@');
\define('TITLE_SEP', ' - ');

\define('MAIL_HOST', 'ssl://smtp.nottingham.ac.uk');
\define('MAIL_PORT', 465);
\define('MAIL_AUTH', false);
\define('MAIL_USER', '');
\define('MAIL_PASS', '');

\define('MAIL_ADMIN', 'psxmp9');
\define('MAIL_ON_CHANGE_USRS', 'psxmp9,pszej');
\define('MAIL_ON_CHANGE_SUBJ', '[' . SITE_NAME . '] Submission Updated for <firstName> <surname>');
\define('MAIL_FORGOT_PASS_SUBJ', '[' . SITE_NAME . '] Password for for <firstName> <surname>');
\define('MAIL_FORGOT_PASS_MESG', "Hi <firstName>,\n\nSomeone requested the password for your account for the " . SITE_NAME . " website.\n\nThe submission system can be found at <a href=\"". URI_ROOT ."/login\" target=\"_blank\">" . URI_ROOT ."/login</a> and you need to use the following details to login:\n<strong>Username:</strong> <username>\n<strong>Password:</strong> <password>\n\nThanks,\nMartin Procheron");

\define('CACHE_GENERAL', 86400);
\define('CACHE_USER', CACHE_GENERAL);
\define('CACHE_KEYWORDS', CACHE_GENERAL);
\define('CACHE_SUBMISSION', CACHE_GENERAL);
\define('CACHE_SEARCH', 604800);
\define('CACHE_SCREEN', 604800);
\define('CACHE_CLEAR_ON_SUBMIT', true);

\date_default_timezone_set('Europe/London');
