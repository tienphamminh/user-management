<?php

if (!defined('_INCODE')) die('Access Denied...');


$dataHeader = [
    'pageTitle' => 'Add New User'
];
addLayout('header', $dataHeader);

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

        // Insert into table 'user'
        $dataInsert = [
            'email' => $email,
            'fullname' => $fullname,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status' => $body['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];
        $isDataInserted = insert('user', $dataInsert);

        if ($isDataInserted) {
            setFlashData('msg', 'User <span class="text-danger">' . $email . '</span> has been added successfully.');
            setFlashData('msg_type', 'success');
            redirect('?module=user&action=list');
        } else {
            setFlashData('msg', 'Something went wrong, please try again.');
            setFlashData('msg_type', 'danger');
        }

    } else {
        // Errors occurred
        setFlashData('msg', 'Please check the input form data.');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old_data', $body);
    }

    redirect('?module=user&action=add');
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('old_data');

?>
    <div class="container">
        <hr/>
        <h3>Add New User</h3>
        <?php echo getMessage($msg, $msgType); ?>
        <form action="" method="post">
            <div class="row">

                <div class="col">
                    <div class="form-group">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Fullname..."
                               value="<?php echo getOldFormValue('fullname', $oldData); ?>">
                        <?php echo getFormError('fullname', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone number</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone number..."
                               value="<?php echo getOldFormValue('phone', $oldData); ?>">
                        <?php echo getFormError('phone', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email..."
                               value="<?php echo getOldFormValue('email', $oldData); ?>">
                        <?php echo getFormError('email', $errors); ?>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password...">
                        <?php echo getFormError('password', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="cf-password">Confirm password</label>
                        <input type="password" class="form-control" name="confirm_password" id="cf-password"
                               placeholder="Confirm password...">
                        <?php echo getFormError('confirm_password', $errors); ?>
                    </div>

                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            <option value="0" <?php echo (getOldFormValue('status', $oldData) == 0) ? 'selected' : null; ?>>
                                Not Active
                            </option>
                            <option value="1" <?php echo (getOldFormValue('status', $oldData) == 1) ? 'selected' : null; ?>>
                                Active
                            </option>
                        </select>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Add User</button>
            <hr>
            <a href="?module=user&action=list" class="btn btn-success btn-sm">Back to User List</a>
        </form>
    </div>

<?php
addLayout('footer');
