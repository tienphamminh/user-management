<?php

if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Login'
];
addLayout('header-login', $data);

//$isMailSend = sendMail('tienteo200vn@gmail.com', 'USER MANAGEMENT SYSTEM TEST', 'Xin chào Tiến, đây là thư thử nghiệm.');
//echo '<br>';
//var_dump($isMailSend);

//echo '<br><br>';

//$dataInsert = ['id' => 1];
//$isSuccess = insert('haha', $dataInsert);
//echo '<br>';
//var_dump($isSuccess);


$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>

    <div class="row">
        <div class="col-6" style="margin: 20px auto;">
            <h3 class="text-center text-uppercase" style="margin-bottom: 40px">Login to System</h3>

            <?php echo getMessage($msg, $msgType) ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="Email address...">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password...">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=forgot">Forgot password?</a></p>
                <p class="text-center">New to System? <a href="?module=auth&action=register">Create an account.</a></p>
            </form>
        </div>
    </div>

<?php
addLayout('footer-login');