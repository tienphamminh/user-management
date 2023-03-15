<?php

if (!defined('_INCODE')) die('Access Denied...');

$data = [
    'pageTitle' => 'Verify Account'
];
addLayout('header-login', $data);

if (!empty(getBody()['token'])) {
    $activeToken = getBody()['token'];
    $sql = "SELECT id, fullname, email FROM user WHERE active_token=:active_token";
    $data = ['active_token' => $activeToken];
    $result = getFirstRow($sql, $data);

    if (!empty($result)) {
        $userId = $result['id'];
        $dataUpdate = [
            'status' => 1,
            'active_token' => null
        ];
        $condition = "id=:id";
        $dataCondition = ['id' => $userId];
        $isDataUpdated = update('user', $dataUpdate, $condition, $dataCondition);

        if ($isDataUpdated) {
            setFlashData('msg', 'Account activated successfully. You can log in now!');
            setFlashData('msg_type', 'success');

            $loginLink = _WEB_HOST_ROOT . '?module=auth&action=login';
            $subject = 'Account activated successfully';
            $content = 'Your account ' . $result['email'] . ' has been activated successfully. <br>';
            $content .= 'You can log in by clicking the link below: <br>' . $loginLink . '<br>';
            $content .= 'Regards.';
            sendMail($result['email'], $subject, $content);

        } else {
            setFlashData('msg', 'Activation failed! Please contact AdminSupport.');
            setFlashData('msg_type', 'danger');
        }

        redirect('?module=auth&action=login');
    }
}

$message = getMessage('Invalid or expired active link.', 'danger');

?>

    <div class="container text-center">
        <br/>
        <?php echo $message ?>
    </div>

<?php
addLayout('header-footer');