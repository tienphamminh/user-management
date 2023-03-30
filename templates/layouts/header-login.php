<?php
if (!defined('_INCODE')) die('Access Denied...');

if (isLoggedIn()) {
    redirect('?module=home&action=welcome');
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo (!empty($dataHeader['pageTitle'])) ? $dataHeader['pageTitle'] : 'User Management'; ?></title>
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet"
          href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css?ver=<?php echo rand(); ?>">
</head>
<body>
