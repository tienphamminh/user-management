<?php

if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Create a New Password'
];
addLayout('header-login', $data);

if (!empty(getBody()['token'])) {
    // Check if forgot token in URL exists in table 'user'
    $forgotToken = getBody()['token'];
    $sql = "SELECT id, email FROM user WHERE forgot_token=:forgot_token";
    $data = ['forgot_token' => $forgotToken];
    $result = getFirstRow($sql, $data);

    if (!empty($result)) {
        $userId = $result['id'];
        $email = $result['email'];

        if (isPost()) {
            $body = getBody();
            $errors = [];

            // New password: Required, >=8 characters
            $newPassword = $body['new_password'];
            if (empty($newPassword)) {
                $errors['new_password']['required'] = 'Required field';
            } else {
                if (strlen($newPassword) < 8) {
                    $errors['new_password']['min'] = 'Password must be at least 8 characters';
                }
            }

            // Confirm new password: Required, match
            $confirmPassword = $body['confirm_password'];
            if (empty($confirmPassword)) {
                $errors['confirm_password']['required'] = 'Required field';
            } else {
                if ($newPassword != $confirmPassword) {
                    $errors['confirm_password']['match'] = 'Those passwords do not match';
                }
            }

            if (empty($errors)) {
                // Update fields: 'password', 'forgot_token' and 'update_at' in table 'user'
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $newPasswordHash,
                    'forgot_token' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];
                $condition = "id=:id";
                $dataCondition = ['id' => $userId];
                $isDataUpdated = update('user', $dataUpdate, $condition, $dataCondition);

                if ($isDataUpdated) {
                    setFlashData('msg', 'Your password has been changed! Login with the new password.');
                    setFlashData('msg_type', 'success');

                    // Send mail
                    $subject = 'Your password has been changed';
                    $content = 'You have successfully changed your account password at ' . date('Y-m-d H:i:s') . '.';
                    sendMail($email, $subject, $content);

                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('msg', 'Something went wrong, please try again.');
                    setFlashData('msg_type', 'danger');
                }

            } else {
                // Errors occurred
                setFlashData('msg', 'Please check the input form data.');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors);
            }

            redirect('?module=auth&action=reset&token=' . $forgotToken);
        }

        $msg = getFlashData('msg');
        $msgType = getFlashData('msg_type');
        $errors = getFlashData('errors');

        ?>
        <div class="row">
            <div class="col-6" style="margin: 20px auto;">
                <h3 class="text-center text-uppercase" style="margin-bottom: 40px">Reset your password</h3>

                <?php echo getMessage($msg, $msgType); ?>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="new-password">New password</label>
                        <input type="password" name="new_password" class="form-control" id="new-password"
                               placeholder="New password...">
                        <?php echo getFormError('new_password', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="cf-password">Confirm new password</label>
                        <input type="password" name="confirm_password" class="form-control" id="cf-password"
                               placeholder="Confirm new password...">
                        <?php echo getFormError('confirm_password', $errors); ?>
                    </div>

                    <input type="hidden" name="token" value="<?php echo $forgotToken; ?>">

                    <button type="submit" class="btn btn-primary btn-block">Change</button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">Back to login</a></p>
                </form>
            </div>
        </div>
        <?php

    } else {
        $message = getMessage('Invalid or expired active link.', 'danger');
    }
} else {
    $message = getMessage('Invalid or expired active link.', 'danger');
}

?>

    <div class="container text-center">
        <br/>
        <?php echo !empty($message) ? $message : null; ?>
    </div>

<?php

addLayout('footer-login');
