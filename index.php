<?php

require_once 'config.php';
require_once 'includes/functions.php';

$module = _DEFAULT_MODULE;
$action = _DEFAULT_ACTION;

if (!empty($_GET['module'])) {
    if (is_string($_GET['module'])) {
        $module = trim($_GET['module']);
    }
}

if (!empty($_GET['action'])) {
    if (is_string($_GET['action'])) {
        $action = trim($_GET['action']);
    }
}


$path = 'modules/' . $module . '/' . $action . '.php';
if (file_exists($path)) {
    require_once $path;
} else {
    require_once 'modules/error/404.php';
}