<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../external_tool/vendor/phpmailer/src/Exception.php';
require_once '../external_tool/vendor/phpmailer/src/PHPMailer.php';
require_once '../external_tool/vendor/phpmailer/src/SMTP.php';


$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER; 

$mail->isSMTP();                      
$mail->Host       = 'your_host'; 
$mail->SMTPAuth   = true; 

$mail->SMTPDebug = 1;
$mail->Username   = 'your_username';
$mail->Password   = 'your_password';   
$mail->SMTPSecure = 'your_port';
$mail->Port       = 000;   //insert correct port number
$mail->isHtml(true); 

return $mail;