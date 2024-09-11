<?php
include_once 'utils/config_and_import.php';

//Check that the user isn't already logged in
$user_id = get_logged_user_id();
if($user_id > 0){
    redirect_to_index();
}

//Check that all needed data was supplied and is a string
check_post_field_array("register", ["first_name", "last_name", "username", "email", "password", "confirm_password"]);

//Check that the email has the correct format
if(!is_valid_email_address($_POST["email"])){
    redirect_with_error("register", "Wrong email address format");
}

//Check that the password has the correct format
$pass_error = check_valid_password($_POST["password"]);
if(!empty($pass_error)){
    redirect_with_error("register", $pass_error);
}

//Check that passwords match
if($_POST["password"] !== $_POST["confirm_password"]){
    redirect_with_error("register", "Passwords do not match");
}

//Check password strength
$pass_strength_warning = check_password_strength($_POST["password"], array_values($_POST));
if(!empty($pass_strength_warning)){
    redirect_with_error("register", $pass_strength_warning);
}

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

//Self-registration functionality can allow an attacker to perform user
//enumeration. Indeed, the attacker can try to register a new user with
//a certain name, and if registration fails then she learns that such a
//username already exists. To avoid user-enumeration, the application 
//returns a non-informative message like “a confirmation email has been
//sent to your email address”. Then, if the user DOES not exists, the
//application sends a confirmation email to the specified email address
//containing a random unpredictable URL. When the user follows this URL,
//the self-registration is complete. Otherwise, if the user already exists,
//the user is notified with an email about this attempt.

//Check if the email is already in use
$query = "SELECT * FROM `users` WHERE `email` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["email"]], "s");
if(count($query_rows) > 0){
    $logger->warning('[REGISTER] e-mail already in use.', ['username' => $_POST["username"], 'email' => $_POST["email"]]);
    $message  = "<p>We wanted to inform you that an attempt was recently made to register a new account ";
    $message .= "on our platform using your email address ({$_POST["email"]}).</p>";
    $message .= "<p>If this was you, no further action is needed, and you can safely ignore this message.</p>";
    $message .= "<p>However, if you did not initiate this registration, we recommend the following actions:</p>";
    $message .= "<ul>";
    $message .= "<li>Ensure that your existing accounts with us and other platforms are secure by changing your passwords.</li>";
    $message .= "<li>Be cautious of any suspicious emails or phishing attempts related to your account security.</li>";
    $message .= "<li>If you have any concerns or need assistance, please contact our support team immediately.</li>";
    $message .= "</ul>";
    $message .= "<p>Thank you for your attention to this matter.</p>";
    send_alert_mail($_POST["email"], $message);
    //Returns a non-informative message
    redirect_with_error("login", "A verification email has been sent to your email address.");
}

//Check if the username is already taken
$query = "SELECT * FROM `users` WHERE `username` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["username"]], "s");
if(count($query_rows) > 0){
    $logger->warning('[REGISTER] Username already taken (?username enumeration?).', ['username' => $_POST["username"]]);
    redirect_with_error("register", "Username already taken.");
}

//Send verification mail
$verification_token = bin2hex(random_bytes(32));
send_verification_mail($_POST["email"], $_POST["username"], $verification_token);

//Insert user into the database
$query = "INSERT INTO `users` (`username`,`first_name`,`last_name`,`email`,`password`,`is_verified`,".
                      "`verif_token`) VALUES".
         "(?, ?, ?, ?, ?, ?, ?)";
$query_result = $db->exec_query("INSERT", $query, [$_POST["username"],$_POST["first_name"],$_POST["last_name"],
                                                   $_POST["email"],password_hash($_POST["password"], PASSWORD_DEFAULT),
                                                   0,$verification_token], "sssssis");

//At this point the registration is done, redirect to the login
redirect_with_error("login", "A verification email has been sent to your email address.");

?>