<?php

define ('DIR', dirname (__FILE__));

require DIR . '/config.php';
require DIR . '/salt.php';

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