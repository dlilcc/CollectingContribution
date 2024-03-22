<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';
function sendMail($email, $title, $message) {
    $mail = new PHPMailer(true);

    $mail->isSMTP(); 
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'dlilcc718@gmail.com';
    $mail->Password = 'jbfd qsqq logu mzgs';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('dlilcc718@gmail.com');

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = $title;
    $mail->Body = $message;
    
    $mail->send();

    return;
}
?>