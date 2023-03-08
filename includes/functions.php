<?php

if (!defined('_INCODE')) die('Access Denied...');

//Import PHPMailer classes into the global namespace
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
function sendMail($to, $subject, $body)
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
        $mail->Password = 'dmtuhcruplertptd';               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    //Enable implicit TLS encryption
        $mail->Port = 465;                                  //TCP

        //Recipients
        $mail->setFrom('tienphamminh0312@gmail.com', 'User Management System');
        $mail->addAddress($to);     //Add a recipient

        //Content
        $mail->isHTML(true);  //Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        return $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}