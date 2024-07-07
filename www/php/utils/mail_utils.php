<?php
// Include the phpmailer loader
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include_once 'logger.php';

function send_verification_mail($email, $receiver_username, $verification_token) : void {
    $message = "Thanks for joining BookEmporium!<br>Click on the following link to verify your email address and complete your registration: https://{$_SERVER['SERVER_NAME']}/php/verify.php?verification_token={$verification_token}";
    send_mail($email, $receiver_username, "BookEmporium Verification", $message);
}

function send_mail($email, $name, $subject, $message) : void{
    //Get Logger instance 
    $logger = Logger::getInstance();
    
    $mail = new PHPMailer(true);
    $error = "";

    try {
        //enable verbose debug output (2 for detailed debug output)
        //$mail->SMTPDebug = 2;

        //using the SMTP protocol to send the email
        $mail->isSMTP();

        $mail->SMTPAuth = true;
        $mail->Host     = 'smtp.gmail.com';
        $mail->Username = getenv('MAIL_ADDRESS');
        $mail->Password = getenv('MAIL_PASSWORD');

        //by setting SMTPSecure to PHPMailer::ENCRYPTION_STARTTLS
        //we are telling PHPMailer to use TLS encryption method 
        //when connecting to the SMTP server. This helps ensure
        //that the communication between our application and the
        //SMTP server is encrypted.
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->SMTPAuth = true;

        //TCP port to connect with Gmail SMTP server.
        $mail->Port = 587;

        //sender information
        $mail->setFrom($mail->Username, 'Book Emporium');

        //receiver email address and name
        $mail->addAddress($email, $name);  

        //by setting IsHTML(true), we inform PHPMailer that the
        //email will include HTML markup.
        $mail->IsHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        $error = "OK";

        // Attempt to send the email
        if (!$mail->send()) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $logger->warning('[SEND_MAIL] Failed attempt', ['result' => $error, 'to' => $email]);
        }

    } catch (Exception $e) {
        $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $logger->warning('[SEND_MAIL] Failed attempt', ['result' => $error, 'to' => $email]);
    }

}

//Example of usage
//$email = 'f.casu1@studenti.unipi.it';
//$name = 'Federico Casu';
//$subject = 'PHPMailer test';
//$message = 'PHPMailer the awesome Package\nPHPMailer is working fine for sending mail\nThis is a tutorial to guide you on PHPMailer integration';
//sendMail($mail, $name, $subject, $message);
?>