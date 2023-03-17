<?php

if (!defined('_INCODE')) die('Access Denied...');

if (!isLoggedIn()) {
    redirect('?module=auth&action=login');
}

echo '<h1> HOME PAGE </h1> <br>';
echo 'Your login_token is: ' . getSession('login_token');
