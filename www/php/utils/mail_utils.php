<?php
// Include the phpmailer loader
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include_once 'logger.php';
include_once 'db_utils.php';

function send_verification_mail($email, $receiver_username, $verification_token) : bool {
    $message = "Thanks for joining BookEmporium!<br>" . 
            "Click on the following link to verify your email address and complete your registration: " . 
            "https://{$_SERVER['SERVER_NAME']}/php/verify.php?verification_token={$verification_token}";
    return send_mail($email, $receiver_username, "BookEmporium Verification", $message);
}

function send_alert_mail($email, $message) : bool {
    return send_mail($email, "", "BookEmporium Alert", $message);
}

function send_reset_mail($email, $receiver_username, $reset_token) : void {
    $message = "Click on the following link to reset your password: https://{$_SERVER['SERVER_NAME']}/pages/password_reset.php?reset_token={$reset_token}".
               "<br>The link will only be valid for the next 30 minutes.";
    send_mail($email, $receiver_username, "BookEmporium Password Reset", $message);
}

function send_mail($email, $name, $subject, $message) : bool {    
    $mail = new PHPMailer(true);

    try {
        //enable verbose debug output (2 for detailed debug output)
        //$mail->SMTPDebug = 2;

        //using the SMTP protocol to send the email
        $mail->isSMTP();

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

        //by setting isHTML(true), we inform PHPMailer that the
        //email will include HTML markup.
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        // Attempt to send the email
        if (!$mail->send()) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            Logger::getInstance()->warning('[SEND_MAIL] Failed attempt', ['result' => $error, 'to' => $email]);
            return false;
        }

        return true;
    } catch (Exception $e) {
        $error = "Message could not be sent. Mailer Error: {$e->getMessage()}";
        Logger::getInstance()->error('[ERROR] Trace: send_mail()', ['error' => $error, 'to' => $email]);
        return false;
    }

}

//Example of usage
//$email = 'recipient@email.com';
//$name = 'FirstName LastName';
//$subject = 'PHPMailer test';
//$message = 'PHPMailer the awesome Package\nPHPMailer is working fine for sending mail\nThis is a tutorial to guide you on PHPMailer integration';
//sendMail($mail, $name, $subject, $message);

// Function to send order summary email
function send_order_summary($user_id, $order_id) : bool {
    $order = get_order_details($user_id, $order_id);
    if (is_null($order) || empty($order)) {
        Logger::getInstance()->error("[ERROR] Order details not found for user_id: $user_id, order_id: $order_id");
        return false;
    }

    $email = get_email_from_user_id($user_id);
    $name = get_full_name_from_user_id($user_id);
    if ($email === "" || $name === "")
        return false;

    // Prepare email content
    $subject = "Book Emporium - Order Summary - Order #$order_id";

    $message  = "<p>Here is the summary of your order:</p>";
    $message .= "<ul>";
    
    foreach ($order['items'] as $item) {
        $message .= "<li><strong>{$item['book_title']}</strong> by {$item['book_author']} (ISBN: {$item['isbn']})</li>";
        $message .= "<ul>";
        $message .= "<li>Price: {$item['price']}</li>";
        $message .= "<li>Quantity: {$item['quantity']}</li>";
        $message .= "</ul>";
    }

    $message .= "</ul>";
    $message .= "<p><b>Total Price</b>:<br>{$order['total_price']}</p>";
    $message .= "<p><b>Billing Address</b>:<br>{$order['billing_address']}<br>{$order['billing_city']}, {$order['billing_postal_code']}<br>{$order['billing_country']}</p>";
    $masked_card_number = '**** **** **** ' . substr($order['card_number'], -4);
    $message .= "<p><b>Payment Details</b>:<br>Card Number: {$masked_card_number}<br>Expiry Date: {$order['expiry_date']}<br>Name: {$order['billing_first_name']} {$order['billing_last_name']}</p>";
    $message .= "<p><b>Shipping Address</b>:<br>{$order['shipping_address']}<br>{$order['shipping_city']}, {$order['shipping_postal_code']}<br>{$order['shipping_country']}</p>";
    $message .= "<p><b>Status</b>:<br>{$order['status_description']}</p>";
    $message .= "<p>Thank you for shopping with us!</p><br>";
    $message .= "<p><b>Book Emporium</b> is dedicated to providing a curated selection of high-quality books across various genres. We believe in the power of literature to inspire, educate, and entertain. Explore our collection and find your next favorite read!</p>";

    // Send email
    if (!send_mail($email, $name, $subject, $message))
        return false;

    return true;
}
?>