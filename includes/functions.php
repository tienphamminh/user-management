<?php

if (!defined('_INCODE')) die('Access Denied...');

function addLayout($layoutName = 'header', $data = []): void
{
    $path = _DIR_PATH_TEMPLATE . '/layouts/' . $layoutName . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
}