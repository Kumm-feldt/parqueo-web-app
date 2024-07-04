<?php
require 'path/to/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_email@example.com';
$mail->Password = 'your_password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('your_email@example.com', 'Vehicle Tracking System');
$mail->addAddress('recipient_email@example.com');

$mail->isHTML(true);

$mail->Subject = 'Vehicle Data';
$mail->Body    = 'Please find the attached vehicle data.';
$mail->addAttachment('VehicleData.xlsx');

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
