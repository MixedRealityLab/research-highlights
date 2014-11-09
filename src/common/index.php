<?php

define ('DIR', dirname (__FILE__));

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

set_include_path (get_include_path () . ':' . DIR_LIB);
spl_autoload_extensions ('.class.php');
spl_autoload_register ();

$page = trim (str_replace (PATH . '/', '', $_SERVER['REQUEST_URI']));
$page = empty ($page) ? 'index' : $page;

if (strpos ($page, 'do/') === 0) {
	$file = DIR_AJX . '/' . substr ($page, 3) . '.php';
} else {
	$file = DIR_PAG . '/' . $page . '.php';
}

if (strpos ($file, '..') === false && is_file ($file)) {
	require $file;
} else {
	header ('Location: ' . URI_HOME . '/');
}