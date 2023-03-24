<?php

if (!defined('_INCODE')) die('Access Denied...');

$dataHeader = [
    'pageTitle' => 'Home'
];
addLayout('header', $dataHeader);

?>

    <div class="container" style="margin: 40px auto">
        <h1> HOME PAGE </h1> <br>
        <h5>Your login_token is: <?php echo getSession('login_token'); ?> </h5>
    </div>

<?php
addLayout('footer');


