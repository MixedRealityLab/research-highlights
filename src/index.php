<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

require 'autoload.php';

$path = \preg_replace('/'. \preg_quote(PATH . '/', '/') .'/', '', $_SERVER['REQUEST_URI'], 1);
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
if (isset($types[$type])) {
    $dir = $types[$type];
} else {
    $dir = $types[''];
    $page .= '.' . $type;
}

$page = $page === '/' ? PAG_HOME : $page;

if (\strpos($page, 'do/') === 0) {
    $file = $types['do'] . '/' . \substr($page, 3) . '.php';
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

$file = \str_replace('/./', '/', $file);

$data = array ();
while (true) {
    if(strpos($file, $dir .'/') === false) {
        break;
    } elseif (\is_file($file)) {
        \array_reverse($data);
        require $file;
        exit;
    } elseif (\strpos($file, '/') !== false) {
        $pathinfo = \pathinfo($file);
        $data[] = $pathinfo['filename'];
        $file = \dirname($file) . '.php';
    } else {
        break;
    }
}

\array_reverse($data);
$home = DIR_WPG .'/'. PAG_HOME .'.php';
if (\is_file($home)) {
    require $home;
    exit;
}

if ('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] != URI_HOME) {
    header('Location: '. URI_HOME);
    exit;
}

die('Could not load page.');