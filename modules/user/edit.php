<?php

if (!defined('_INCODE')) die('Access Denied...');


$dataHeader = [
    'pageTitle' => 'Edit User'
];
addLayout('header', $dataHeader);

$body = getBody();

if (!empty($body['id'])) {
    $userId = $body['id'];
    if ($userId != getSession('id')) {
        $sql = "SELECT * FROM user WHERE id=:id";
        $data = ['id' => $userId];
        $userDetails = getFirstRow($sql, $data);
        if (!empty($userDetails)) {
            setFlashData('user_details', $userDetails);
        } else {
            setFlashData('msg', 'Can not edit! User with ID: ' . $userId . ' does not exist in system.');
            setFlashData('msg_type', 'danger');
            redirect('?module=user&action=list');
        }
    } else {
        setFlashData('msg', 'Can not edit! You are online now.');
        setFlashData('msg_type', 'danger');
        redirect('?module=user&action=list');
    }

} else {
    redirect('?module=user&action=list');
}

if (isPost()) {
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
            $sql = "SELECT id FROM user WHERE email=:email AND id<>:id";
            $data = ['email' => $email, 'id' => $userId];
            if (getNumberOfRows($sql, $data) > 0) {
                $errors['email']['unique'] = 'An account with this email address already exists';
            }
        }
    }

    // Password: Required, >=8 characters
    $password = $body['password'];
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors['password']['min'] = 'Password must be at least 8 characters';
        }
    }

    // Confirm password: Required, match
    $confirmPassword = $body['confirm_password'];
    if (!empty($password)) {
        if ($password != $confirmPassword) {
            $errors['confirm_password']['match'] = 'Those passwords do not match';
        }
    }

    if (empty($errors)) {
        // Validation successful

        // Update user in table 'user'
        $dataUpdate = [
            'email' => $email,
            'fullname' => $fullname,
            'phone' => $phone,
            'status' => $body['status'],
            'update_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($password)) {
            $dataUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $condition = "id=:id";
        $dataCondition = ['id' => $userId];
        $isDataUpdated = update('user', $dataUpdate, $condition, $dataCondition);

        if ($isDataUpdated) {
            setFlashData('msg', 'User has been updated successfully.');
            setFlashData('msg_type', 'success');
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

    redirect('?module=user&action=edit&id=' . $userId);
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$userDetails = getFlashData('user_details');

?>
    <div class="container">
        <hr/>
        <h3>Edit User</h3>
        <?php echo getMessage($msg, $msgType); ?>
        <form action="" method="post">
            <div class="row">

                <div class="col">
                    <div class="form-group">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Fullname..."
                               value="<?php echo getOldFormValue('fullname', $userDetails); ?>">
                        <?php echo getFormError('fullname', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone number</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone number..."
                               value="<?php echo getOldFormValue('phone', $userDetails); ?>">
                        <?php echo getFormError('phone', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email..."
                               value="<?php echo getOldFormValue('email', $userDetails); ?>">
                        <?php echo getFormError('email', $errors); ?>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password"
                               placeholder="Password (Leave blank if no change)...">
                        <?php
                        echo getFormError('password', $errors);
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="cf-password">Confirm password</label>
                        <input type="password" class="form-control" name="confirm_password" id="cf-password"
                               placeholder="Confirm password (Leave blank if no change)...">
                        <?php echo getFormError('confirm_password', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            <option value="0" <?php echo (getOldFormValue('status', $userDetails) == 0) ? 'selected' : null; ?>>
                                Not Active
                            </option>
                            <option value="1" <?php echo (getOldFormValue('status', $userDetails) == 1) ? 'selected' : null; ?>>
                                Active
                            </option>
                        </select>
                    </div>
                </div>

            </div>

            <input type="hidden" name="id" value="<?php echo $userId ?>">
            <button type="submit" class="btn btn-primary">Update User</button>
            <hr>
            <a href="?module=user&action=list" class="btn btn-success btn-sm">Back to User List</a>
        </form>
    </div>

<?php
addLayout('footer');
