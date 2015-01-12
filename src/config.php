<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

\define ('DIR_WAJ', DIR . '/web/ajax');
\define ('DIR_WCS', DIR . '/web/css');
\define ('DIR_WJS', DIR . '/web/js');
\define ('DIR_WPG', DIR . '/web/page');
\define ('DIR_WTP', DIR . '/web/tpl');

\define ('DIR_DAT', \dirname (DIR) . '/dat');

\define ('DIR_SLB', DIR .  '/sys/lib');
\define ('DIR_SCS', DIR . '/sys/css');
\define ('DIR_SJS', DIR . '/sys/js');

\define ('DIR_USR', DIR . '/usr');

\define ('DOMAIN', '@@@DOMAIN@@@');
\define ('PATH', '@@@PATH@@@');

\define ('PAG_HOME', 'index');
\define ('SYS_HTAC', false);

\define ('URI_HOME', DOMAIN . PATH);
\define ('URI_ROOT', URI_HOME . SYS_HTAC ? '' : '/index.php');
\define ('URI_WEB', URI_HOME . '/web');
\define ('URI_SYS', URI_HOME . '/sys');
\define ('URI_DATA', \dirname (URI_HOME) . '/dat');

\define ('MAIL_HOST', 'ssl://mail.nottingham.ac.uk');
\define ('MAIL_PORT', 465);
\define ('MAIL_AUTH', false);
\define ('MAIL_USER', '');
\define ('MAIL_PASS', '');

\date_default_timezone_set ('Europe/London');