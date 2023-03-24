<?php
if (!defined('_INCODE')) die('Access Denied...');

if (!isLoggedIn()) {
    redirect('?module=auth&action=login');
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
<header>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">

            <a class="navbar-brand" href="?module=home&action=welcome">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/cr7-logo.jpg" width="40" height="40" alt=""> CraterT
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="?module=home&action=welcome">Home</a>
                    </li>

                    <li class="nav-item active">
                        <a class="nav-link" href="?module=user&action=list">List</a>
                    </li>

                    <li class="nav-item dropdown profile">

                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hi, <?php echo !empty($userDetail['fullname']) ? $userDetail['fullname'] : 'CraterT'; ?>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">My Profile</a>
                            <a class="dropdown-item" href="#">Change Password</a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               href="?module=auth&action=logout">Logout</a>
                        </div>

                    </li>

                </ul>
            </div>
        </nav>
    </div>
</header>
