<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

\define ('VERSION', 'CDT-RH/3.0');

\define ('DIR_AJX', DIR . '/app/ajax');
\define ('DIR_PAG', DIR . '/app/page');
\define ('DIR_TPL', DIR . '/app/tpl');
\define ('DIR_DAT', \dirname (DIR) . '/dat');
\define ('DIR_LIB', DIR .  '/sys/lib');
\define ('DIR_USR', DIR . '/usr');

\define ('DOMAIN', 'http://cdt.horizon.ac.uk');
\define ('PATH', '/rh-submit');

\define ('URI_HOME', DOMAIN . PATH);
\define ('URI_DATA', \dirname (URI_HOME) . '/dat');

\define ('PAG_HOME', 'index');
\define ('SYS_HTAC', false);

\define ('MAIL_HOST', 'ssl://smtp.nottingham.ac.uk');
\define ('MAIL_PORT', 465);
\define ('MAIL_AUTH', false);
\define ('MAIL_USER', '');
\define ('MAIL_PASS', '');

\date_default_timezone_set ('Europe/London');