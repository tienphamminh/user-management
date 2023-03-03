<?php

// Prevent direct access to files from URL
const _INCODE = true;

const _DEFAULT_MODULE = 'home';
const _DEFAULT_ACTION = 'welcome';

define('_WEB_HOST_ROOT', 'http://' . $_SERVER['HTTP_HOST'] . '/user-management');
const _WEB_HOST_TEMPLATE = _WEB_HOST_ROOT . '/templates';

const _DIR_PATH_ROOT = __DIR__;
const _DIR_PATH_TEMPLATE = _DIR_PATH_ROOT . '/templates';


