<?php
require '../vendor/autoload.php';

$env = file_get_contents("../.env");
$lines = explode("\n",$env);

foreach($lines as $line){
  preg_match("/([^#]+)\=(.*)/",$line,$matches);
  if(isset($matches[2])){ putenv(trim($line)); }
} 
use PHPMailer\PHPMailer\PHPMailer;

// Now send the email with the Excel attachment using PHPMailer
$mail = new PHPMailer(true);

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = getenv('HOST');
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = getenv('USER_NAME');
    $mail->Password = getenv('PASSWORD'); 

    $mail->isHTML(true); // Set email format to HTML

    $mail->setFrom(getenv('EMAIL'), 'amiparqueo.com');
    
    $mail->addReplyTo(getenv('EMAIL'), 'amiparqueo.com');