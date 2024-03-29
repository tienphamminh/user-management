<?php

if (!defined('_INCODE')) die('Access Denied...');

$dataHeader = [
    'pageTitle' => 'Login'
];
addLayout('header-login', $dataHeader);

if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email'])) && !empty($body['password'])) {
        $email = $body['email'];
        $password = $body['password'];

        // Check if email address exists in table 'user'
        $sql = "SELECT id, fullname, password FROM user WHERE email=:email AND status=:status";
        $data = [
            'email' => $email,
            'status' => 1
        ];
        $result = getFirstRow($sql, $data);

        if (!empty($result)) {
            $userId = $result['id'];
            $fullname = $result['fullname'];

            // Check if password matches a hashed password in database
            $hashedPassword = $result['password'];
            $isPasswordMatch = password_verify($password, $hashedPassword);
            if ($isPasswordMatch) {
                // Create login token
                $loginToken = sha1(uniqid() . time());
                // Insert into table 'login_token'
                $dataInsert = [
                    'user_id' => $userId,
                    'token' => $loginToken,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                $isDataInserted = insert('login_token', $dataInsert);

                if ($isDataInserted) {
                    saveActivity($userId);
                    setSession('login_token', $loginToken);
                    setSession('id', $userId);
                    setSession('fullname', $fullname);
                    redirect('?module=home&action=welcome');
                } else {
                    setFlashData('msg', 'Something went wrong, please try again.');
                    setFlashData('msg_type', 'danger');
                    redirect('?module=auth&action=login');
                }
            }
        }
        setFlashData('msg', 'Incorrect email address or password (or not active).');

    } else {
        setFlashData('msg', 'Please enter your email and password.');
    }

    setFlashData('msg_type', 'danger');
    redirect('?module=auth&action=login');
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>

    <div class="row">
        <div class="col-3" style="margin: 20px auto;">
            <h3 class="text-center text-uppercase" style="margin-bottom: 40px">Login to System</h3>

            <?php echo getMessage($msg, $msgType) ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email address...">
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
