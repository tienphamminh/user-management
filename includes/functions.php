<?php

if (!defined('_INCODE')) die('Access Denied...');

function layout($layoutName = 'header', $data = [])
{
    $path = _DIR_PATH_TEMPLATE . '/layouts/' . $layoutName . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
}