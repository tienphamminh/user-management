<?php

if (!defined('_INCODE')) die('Access Denied...');

if (isLoggedIn()) {
    $loginToken = getSession('login_token');
    $condition = "token=:token";
    $dataCondition = ['token' => $loginToken];
    delete('login_token', $condition, $dataCondition);
    removeSession();
}
redirect('?module=auth&action=login');
