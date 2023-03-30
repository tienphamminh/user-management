<?php

if (!defined('_INCODE')) die('Access Denied...');

$dataHeader = [
    'pageTitle' => 'Register'
];
addLayout('header-login', $dataHeader);

if (isPost()) {
    $body = getBody();
    $errors = [];

    // Full name: Required, >=5 characters
    $fullname = trim($body['fullname']);
    if (empty($fullname)) {
        $errors['fullname']['required'] = 'Required field';
    } else {
        if (strlen($fullname) < 5) {
            $errors['fullname']['min'] = 'Full name must be at least 5 characters';
        }
    }

    // Phone number: Required, valid format
    $phone = trim($body['phone']);
    if (empty($phone)) {
        $errors['phone']['required'] = 'Required field';
    } else {
        if (!isPhone($phone)) {
            $errors['phone']['isPhone'] = 'Invalid phone number format';
        }
    }

    // Email: Required, valid format, unique
    $email = trim($body['email']);
    if (empty($email)) {
        $errors['email']['required'] = 'Required field';
    } else {
        if (!isEmail($email)) {
            $errors['email']['isEmail'] = 'Invalid email address';
        } else {
            $sql = "SELECT id FROM user WHERE email=:email";
            $data = ['email' => $email];
            if (getNumberOfRows($sql, $data) > 0) {
                $errors['email']['unique'] = 'An account with this email address already exists';
            }
        }
    }

    // Password: Required, >=8 characters
    $password = $body['password'];
    if (empty($password)) {
        $errors['password']['required'] = 'Required field';
    } else {
        if (strlen($password) < 8) {
            $errors['password']['min'] = 'Password must be at least 8 characters';
        }
    }

    // Confirm password: Required, match
    $confirmPassword = $body['confirm_password'];
    if (empty($confirmPassword)) {
        $errors['confirm_password']['required'] = 'Required field';
    } else {
        if ($password != $confirmPassword) {
            $errors['confirm_password']['match'] = 'Those passwords do not match';
        }
    }

    if (empty($errors)) {
        // Validation successful
        // Create active token
        $activeToken = sha1(uniqid() . time());
        // Insert into table 'user'
        $dataInsert = [
            'email' => $email,
            'fullname' => $fullname,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'active_token' => $activeToken,
            'create_at' => date('Y-m-d H:i:s')
        ];
        $isDataInserted = insert('user', $dataInsert);

        if ($isDataInserted) {
            // Create active link
            $activeLink = _WEB_HOST_ROOT . '?module=auth&action=active&token=' . $activeToken;
            // Send mail
            $subject = 'Please verify your account';
            $content = 'Hi ' . $fullname . '! <br/>';
            $content .= 'Please click the link below to verify your account: <br/>';
            $content .= $activeLink . '<br/>';
            $content .= 'Regards.';
            $isMailSent = sendMail($email, $subject, $content);

            if ($isMailSent) {
                setFlashData('msg', 'Registration successful! Please check your email to verify your account.');
                setFlashData('msg_type', 'success');
                redirect('?module=auth&action=register');
            }
        }

        setFlashData('msg', 'Something went wrong, please try again.');
        setFlashData('msg_type', 'danger');

    } else {
        // Errors occurred
        setFlashData('msg', 'Please check the input form data.');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old_data', $body);
    }

    redirect('?module=auth&action=register');
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('old_data');

?>

    <div class="row">
        <div class="col-4" style="margin: 20px auto;">
            <h3 class="text-center text-uppercase" style="margin-bottom: 40px">Create an Account</h3>
            <?php echo getMessage($msg, $msgType); ?>

            <form action="" method="post">

                <div class="form-group">
                    <label for="fullname">Full name</label>
                    <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Full name..."
                           value="<?php echo getOldFormValue('fullname', $oldData); ?>">
                    <?php echo getFormError('fullname', $errors); ?>
                </div>

                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number..."
                           value="<?php echo getOldFormValue('phone', $oldData); ?>">
                    <?php echo getFormError('phone', $errors); ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="Email address..."
                           value="<?php echo getOldFormValue('email', $oldData); ?>">
                    <?php echo getFormError('email', $errors); ?>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password...">
                    <?php echo getFormError('password', $errors); ?>
                </div>

                <div class="form-group">
                    <label for="cf-password">Confirm password</label>
                    <input type="password" name="confirm_password" class="form-control" id="cf-password"
                           placeholder="Confirm password...">
                    <?php echo getFormError('confirm_password', $errors); ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Register</button>

                <hr>
                <p class="text-center">Already have an account? <a href="?module=auth&action=login">Login</a></p>
            </form>
        </div>
    </div>

<?php
addLayout('footer-login');
