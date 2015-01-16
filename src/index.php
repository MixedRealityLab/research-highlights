<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

\define ('DIR', \dirname (__FILE__));

require DIR . '/_version.php';
require 'config.php';
require 'salt.php';

\set_include_path (\get_include_path () . PATH_SEPARATOR . DIR_SLB . PATH_SEPARATOR . DIR_SLB .'/PEAR');
\spl_autoload_extensions ('.class.php,.php');
\spl_autoload_register ();

$page = \trim (\str_replace (PATH . '/', '', $_SERVER['REQUEST_URI']));
if (!SYS_HTAC) {
	$page = \trim (\str_replace ('index.php/', '', $page));
}
$page = empty ($page) ? PAG_HOME : $page;

if (\strpos ($page, 'do/') === 0) {
	$file = DIR_WAJ . '/' . \substr ($page, 3) . '.php';
} else {
	$file = DIR_WPG . '/' . $page . '.php';
}

if (\strpos ($file, '..') === false && \is_file ($file)) {
	require $file;
} else if ('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] != URI_HOME) {
	\header ('Location: ' . URI_HOME . '/');
}