<?php
include_once "utils/config_and_import.php";

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

// Check that the user is logged in
$user_id = get_logged_user_id();
if($user_id < 0) {
    $logger->warning('[PASSWORD_CHANGE] Attempt to change password logged in.');
    redirect_with_error("login", "Please, log in before attempting to change your password.");
}

//Guard against CSRF attacks
if(!isset($_POST["csrf_token"]) || !is_string($_POST["csrf_token"])){
    $logger->warning('[PASSWORD_CHANGE] PASSWORD_CHANGE called without a CSRF token.');
    redirect_to_index();
}

if(!verify_and_regenerate_csrf_token($_POST["csrf_token"])){
    $logger->warning('[PASSWORD_CHANGE] CSRF tokens do not match.', 
                     ['form_token' => $_POST["csrf_token"]]);
    redirect_to_index();
}

//Check if the user is timed-out
$query = "SELECT * FROM `wrong_login` WHERE `user_id` = ? AND `created_at` > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
$query_rows = $db->exec_query("SELECT", $query, [$user_id], "i");
$is_timed_out = count($query_rows) >= $lockout_threshold;

//Check if the password is correct
$password_hash = get_password_from_user_id($user_id);
$is_password_correct = password_verify($_POST["password"], $password_hash);

//Check if the user is timed-out
if($is_timed_out){
    
    $username = get_username_from_user_id($user_id);
    $email = get_email_from_user_id($user_id);
    
    // Log a warning about too many failed attempts
    $logger->warning('[PASSWORD_CHANGE] Too many recently failed password change attempts.', ['user_id' => $user_id]);
    
    // Construct the message for the email alert
    $message = "Dear {$username},\n\nWe have detected multiple failed password change attempts on your ";
    $message .= "account at BookEmporium. If this was not you, please take appropriate actions such as ";
    $message .= "logging out of every website you are currently logged in or contacting support.";
    
    // Send the email notification to the user
    //send_mail($email, $username, "BookEmporium Alert: Security Notification", $message);

    // Redirect the user with a generic error message
    redirect_with_error("start_password_change", "Invalid password. Multiple unsuccessful attempts may temporarily restrict access for security reasons.");
}

if(!$is_password_correct){
    $logger->warning('[PASSWORD_CHANGE] Password is not correct.', ['user_id' => $user_id]);

    //Log wrong login attempt
    $query = "INSERT INTO `wrong_login` (`user_id`) VALUES (?)";
    $query_result = $db->exec_query("INSERT", $query, [$user_id], "i");

    //Redirect user
    redirect_with_error("start_password_change", "Invalid password. Multiple unsuccessful attempts may temporarily restrict access for security reasons.");
}

//Set a reset token for the user
$reset_token = bin2hex(random_bytes(32));
$query = "UPDATE `users` SET `reset_token` = ?, `reset_valid_until` = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE `id` = ?";
$query_rows = $db->exec_query("UPDATE", $query, [$reset_token, $user_id], "si");

//Redirect user to the password change form
redirect_to_page("password_reset", "reset_token={$reset_token}");

?>