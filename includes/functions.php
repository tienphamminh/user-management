<?php

if (!defined('_INCODE')) die('Access Denied...');

//Import PHPMailer classes into the global namespace
use JetBrains\PhpStorm\NoReturn;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function addLayout($layoutName = 'header', $data = []): void
{
    $path = _DIR_PATH_TEMPLATE . '/layouts/' . $layoutName . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
}

// Send mail using phpmailer
function sendMail($to, $subject, $body): bool
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                 //Enable verbose debug output
        $mail->isSMTP();                                    //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                             //Enable SMTP authentication
        $mail->Username = 'tienphamminh0312@gmail.com';     //SMTP username
        $mail->Password = '';               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    //Enable implicit TLS encryption
        $mail->Port = 465;                                  //TCP
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Recipients
        $mail->setFrom('tienphamminh0312@gmail.com', 'User Management System');
        $mail->addAddress($to);     //Add a recipient

        //Content
        $mail->isHTML(true);  //Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Check if a request is POST
function isPost(): bool
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }

    return false;
}

// Check if a request is GET
function isGet(): bool
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }

    return false;
}

function getBody(): array
{
    $bodyArr = [];

    if (isGet()) {
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }

            }
        }

    }

    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }

            }
        }
    }

    return $bodyArr;
}

// Check if an input string is Email
function isEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Check if an input string is INT (Ex: $range = ['min_range'=>1, 'max_range'=>20])
function isNumberInt($number, $range = [])
{
    if (!empty($range)) {
        $options = ['options' => $range];
        return filter_var($number, FILTER_VALIDATE_INT, $options);
    } else {
        return filter_var($number, FILTER_VALIDATE_INT);
    }
}

// Check if an input string is Float (Ex: $range = ['min_range'=>1, 'max_range'=>20])
function isNumberFloat($number, $range = [])
{
    if (!empty($range)) {
        $options = ['options' => $range];
        return filter_var($number, FILTER_VALIDATE_FLOAT, $options);
    } else {
        return filter_var($number, FILTER_VALIDATE_FLOAT);
    }
}

// Check if an input string is VN phone number
function isPhone($phone): bool
{
    $checkFirstZero = false;

    if ($phone[0] == '0') {
        $checkFirstZero = true;
        $phone = substr($phone, 1);
    }

    $checkNumberLast = false;

    if (isNumberInt($phone) && strlen($phone) == 9) {
        $checkNumberLast = true;
    }

    if ($checkFirstZero && $checkNumberLast) {
        return true;
    }

    return false;
}

#[NoReturn] function redirect($url = 'index.php'): void
{
    // Send a "Location:" header and a REDIRECT (302) status code back to the client
    header("Location: $url");
    exit;
}

// Get contextual feedback messages (Ex: $context = 'success', 'danger', 'warning' )
function getMessage($msg, $context): ?string
{
    if (!empty($msg)) {
        return '<div class="alert alert-' . $context . '">' . $msg . '</div>';
    }

    return null;
}

// Get form validation errors
function getFormError($fieldName, $errors): ?string
{
    if (!empty($errors[$fieldName])) {
        return '<span class="error">' . reset($errors[$fieldName]) . '</span>';
    }

    return null;
}

// Get old form value
function getOldFormValue($fieldName, $oldDData)
{
    if (!empty($oldDData[$fieldName])) {
        return $oldDData[$fieldName];
    }

    return null;
}

function isLoggedIn()
{
    if (getSession('login_token')) {
        $loginToken = getSession('login_token');

        // Check if $_SESSION['login_token'] exists in table 'login_token'
        $sql = "SELECT user_id FROM login_token WHERE token=:token";
        $data = ['token' => $loginToken];
        $result = getFirstRow($sql, $data);

        if (!empty($result)) {
            return $result;
        } else {
            removeSession('login_token');
        }
    }

    return false;
}
