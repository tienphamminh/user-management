<?php

if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Forgot Password'
];
addLayout('header-login', $data);
?>

    <div class="row">
        <div class="col-6" style="margin: 20px auto;">
            <h3 class="text-center text-uppercase" style="margin-bottom: 40px">Reset your password</h3>

            <form method="post" action="">
                <div class="form-group">
                    <label for="email">Enter your user account's verified email address and we will send you a password
                        reset link.</label>
                    <input type="email" name="email" class="form-control" id="email"
                           placeholder="Enter your email address...">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Send password reset email</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=login">Back to login</a></p>
            </form>
        </div>
    </div>

<?php
addLayout('footer-login');
