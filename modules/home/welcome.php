<?php

if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Home'
];
addLayout('header', $data);

?>

    <h1> HOME PAGE </h1> <br>
    Your login_token is: <?php echo getSession('login_token'); ?>

<?php
addLayout('footer');


