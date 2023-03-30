<?php

if (!defined('_INCODE')) die('Access Denied...');

$dataHeader = [
    'pageTitle' => 'Forgot Password'
];
addLayout('header-login', $dataHeader);


if (isPost()) {
    $body = getBody();

    if (!empty($body['email'])) {
        $email = $body['email'];

        // Check if email address exists in table 'user'
        $sql = "SELECT id, fullname FROM user WHERE email=:email";
        $data = ['email' => $email];
        $result = getFirstRow($sql, $data);

        if (!empty($result)) {
            // Create forgot token
            $forgotToken = sha1(uniqid() . time());

            // Update field: 'forgot_token' in table 'user'
            $userId = $result['id'];
            $dataUpdate = ['forgot_token' => $forgotToken];
            $condition = "id=:id";
            $dataCondition = ['id' => $userId];
            $isDataUpdated = update('user', $dataUpdate, $condition, $dataCondition);

            if ($isDataUpdated) {
                // Create reset link
                $resetLink = _WEB_HOST_ROOT . '?module=auth&action=reset&token=' . $forgotToken;
                // Send mail
                $subject = 'Reset your password';
                $content = 'Hi ' . $result['fullname'] . '!<br>';
                $content .= 'We received a request to reset the password for your account. <br>';
                $content .= 'To reset your password, click the link below: <br>' . $resetLink . '<br>';
                $content .= 'Regards.';
                $sendStatus = sendMail($email, $subject, $content);

                if ($sendStatus) {
                    $resendForm =
                        '<form method="post" action="">
                            <input type="hidden" name="email" value="' . $email . '">
                            If you did not receive this email, please try to
                            <button type="submit" class="btn btn-link">resend the email</button>
                         </form>';
                    $message = 'Check your email! Password reset request was sent successfully.';
                    $message .= $resendForm;
                    setFlashData('msg', $message);
                    setFlashData('msg_type', 'success');

                } else {
                    setFlashData('msg', 'Something went wrong, please try again.');
                    setFlashData('msg_type', 'danger');
                }

            } else {
                setFlashData('msg', 'Something went wrong, please try again.');
                setFlashData('msg_type', 'danger');
            }

        } else {
            setFlashData('msg', 'Incorrect email address.');
            setFlashData('msg_type', 'danger');
        }

    } else {
        setFlashData('msg', 'Please enter your email.');
        setFlashData('msg_type', 'danger');
    }

    redirect('?module=auth&action=forgot');
}


$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');

?>

    <div class="row">
        <div class="col-4" style="margin: 20px auto;">
            <h3 class="text-center text-uppercase" style="margin-bottom: 40px">Reset your password</h3>

            <?php echo getMessage($msg, $msgType); ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="email">Enter your user account's verified email address, and we will send you a password
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
