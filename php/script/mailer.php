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
$mail->Host       = 'smtp.gmail.com'; 
$mail->SMTPAuth   = true; 

$mail->SMTPDebug = 1;
$mail->Username   = 'GeCarSawProject@gmail.com';
$mail->Password   = 'evnl tnxp czyj osmu';   
$mail->SMTPSecure = 'ssl';
$mail->Port       = 465;  
$mail->isHtml(true); 

return $mail;