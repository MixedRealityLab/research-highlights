<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

require 'autoload.php';

$path = \trim (\str_replace (PATH . '/', '', $_SERVER['REQUEST_URI']));
if (SYS_HTAC && \strpos ($_SERVER['REQUEST_URI'], 'index.php/') !== false) {
	header ('Location: ' . \str_replace ('index.php/', '', $_SERVER['REQUEST_URI']));
	exit;
} else if (SYS_HTAC) {
	$path = \trim (\str_replace ('index.php/', '', $path));
}

$pathinfo = \pathinfo ($path);
$page = $pathinfo['filename'];
$type = $pathinfo['extension'];

$types = ['xml' => DIR_WXM, 'go' => DIR_WGO, 'do' => DIR_WAJ, '' => DIR_WPG];
$dir = $types[$type];

$page = empty ($page) ? PAG_HOME : $page;

if (\strpos ($page, 'do/') === 0) {
	$file = DIR_WAJ . '/' . \substr ($page, 3) . '.php';
} else if (\strpos ($page, 'go/') === 0) {
	$end = \strpos ($page, '/') + 2; $end = $end === false ? strlen ($page) : $end;
	$file = $dir . '/' . \substr ($page, 3, $end) . '.php';
} else {
	$file = $dir . '/' . $page . '.php';
}

if (\strpos ($file, '..') === false && \is_file ($file)) {
	require $file;
} else if ('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] != URI_HOME) {
	\header ('Location: ' . URI_HOME . '/');
}