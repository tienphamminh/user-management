<?php

if (!defined('_INCODE')) die('Access Denied...');


if (isPost()) {
    if (!empty(getBody()['id'])) {
        $userId = getBody()['id'];

        if ($userId != getSession('id')) {

            $sql = "SELECT id FROM user WHERE id=:id";
            $data = ['id' => $userId];
            if (getNumberOfRows($sql, $data) > 0) {
                // Delete login_token, then delete user
                $condition = "user_id=:user_id";
                $dataCondition = ['user_id' => $userId];
                $isLoginTokenDeleted = delete('login_token', $condition, $dataCondition);

                if ($isLoginTokenDeleted) {
                    $condition = "id=:id";
                    $dataCondition = ['id' => $userId];
                    $isUserDeleted = delete('user', $condition, $dataCondition);

                    if ($isUserDeleted) {
                        setFlashData('msg', 'User with ID: ' . $userId . ' has been deleted successfully.');
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
                setFlashData('msg', 'Can not delete! User with ID: ' . $userId . ' does not exist in system.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Can not delete! You are online now.');
            setFlashData('msg_type', 'danger');
        }

    } else {
        setFlashData('msg', 'Something went wrong, please try again.');
        setFlashData('msg_type', 'danger');
    }
}

redirect('?module=user&action=list');