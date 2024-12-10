<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

// Now send the email with the Excel attachment using PHPMailer
$mail = new PHPMailer(true);

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'updates@amiparqueo.com';
    $mail->Password = 'jhkalmi85!A'; 

    $mail->isHTML(true); // Set email format to HTML

    $mail->setFrom('updates@amiparqueo.com', 'amiparqueo.com');
    
    $mail->addReplyTo('updates@amiparqueo.com', 'amiparqueo.com'); // correo creado en hostinger