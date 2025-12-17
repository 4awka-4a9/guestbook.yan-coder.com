<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require_once("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

//connect to database

$dsn = "mysql:host=" . HOST . ";dbname=" . DBNAME . ";charset=" . CHARSET;
try {
  $pdo = new PDO($dsn, USER, PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection lost: " . $e->getMessage());
}

//reset password email send

function generateRandomString($length = 100)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
  $charactersLength = strlen($characters);
  $randomString = '';

  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[random_int(0, $charactersLength - 1)];
  }

  return $randomString;
}

function sendEmail($to, $subject, $html_body)
{

  $mail = new PHPMailer(true);

  try {

    //OUTLOOK, to create app password visit https://stackoverflow.com/questions/79057624/smtpauthenticationerror-basic-authentication-is-disabled-for-outlook-live-com
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
//        $mail->isSMTP();                                            //Send using SMTP
//        $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
//        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//        $mail->Username   = 'youremail@outlook.com';                     //SMTP username
//        $mail->Password   = 'APP_PASSWORD_HERE';                               //SMTP password
//        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
//        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
//        $mail->CharSet = 'UTF-8';

    //GMAIL, to create app password visit https://myaccount.google.com/apppasswords
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through         //Enable SMTP authentication
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_LOGIN;                     //SMTP username
    $mail->Password = EMAIL_PASSWORD;                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->CharSet = 'UTF-8';

    //Recipients
    $mail->setFrom(EMAIL_LOGIN, 'Guestbook');
    $mail->addAddress($to);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $html_body;

    $mail->send();
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}

//validate email

function validateEMAIL($email)
{
  $v = "/[a-zA-Z0-9_.+ -]+@[a-zA-Z0-9-]+\.[a-zA-Z]+/";

  return (bool) preg_match($v, $email);
}

?>