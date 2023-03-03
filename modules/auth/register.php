<?php

if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Register'
];
layout('header-login', $data);
?>

    <div class="row">
        <div class="col-6" style="margin: 20px auto;">
            <h3 class="text-center text-uppercase">Create an Account</h3>

            <form action="" method="post">
                <div class="form-group">
                    <label for="fullname">Full name</label>
                    <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Full name..."
                           value="">
                </div>

                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number..."
                           value="">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="Email address..."
                           value="">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password...">
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm password</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirm-password"
                           placeholder="Confirm password...">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Register</button>
                <hr>
                <p class="text-center">Already have an account? <a href="?module=auth&action=login">Login</a></p>
            </form>
        </div>
    </div>

<?php
layout('footer-login');