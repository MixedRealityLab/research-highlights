<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

require 'autoload.php';

$path = \trim(\str_replace(PATH . '/', '', $_SERVER['REQUEST_URI']));
if (SYS_HTAC && \strpos($_SERVER['REQUEST_URI'], 'index.php/') !== false) {
    header('Location: ' . \str_replace('index.php/', '', $_SERVER['REQUEST_URI']));
    exit;
} elseif (SYS_HTAC) {
    $path = \trim(\str_replace('index.php/', '', $path));
}

$pathinfo = \pathinfo($path);
$page = $pathinfo['dirname'] . '/' . $pathinfo['filename'];
$type = $pathinfo['extension'];

$types = ['xml' => DIR_WXM, 'go' => DIR_WGO, 'do' => DIR_WAJ, '' => DIR_WPG];
$dir = $types[$type];

$page = $page === '/' ? PAG_HOME : $page;

if (\strpos($page, 'do/') === 0) {
    $file = DIR_WAJ . '/' . \substr($page, 3) . '.php';
} elseif (\strpos($page, 'go/') === 0) {
    $end = \strpos($page, '/') + 2;
    $end = $end === false ? strlen($page) : $end;
    $file = $dir . '/' . \substr($page, 3, $end) . '.php';
} else {
    $file = $dir . '/' . $page . '.php';
}

if (\strpos($file, '..') !== false) {
    \header('Location: ' . URI_HOME . '/');
    exit;
}

$data = array ();
while (true) {
    if (\is_file($file)) {
        require $file;
        exit;
    } elseif (\strpos($file, '/') !== false) {
        $data[] = $pathinfo['filename'];
        $file = \dirname($file) . '.php';
    } else {
        break;
    }
}

if ('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] != URI_HOME) {
    die('a Location: ' . URI_HOME . '/');
    exit;
}
