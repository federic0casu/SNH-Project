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
$pass_strength_warning = check_password_strength($_POST["password"], $_POST);
if(!empty($pass_strength_warning)){
    redirect_with_error("register", $pass_strength_warning);
}

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

//Check if the username is already taken
$query = "SELECT * FROM `users` WHERE `username` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["username"]], "s");
if(count($query_rows) > 0){
    $logger->warning('[REGISTER] Username already taken.', ['username' => $_POST["username"]]);
    redirect_with_error("register", "Username already taken");
}

//Check if the username is already taken
$query = "SELECT * FROM `users` WHERE `email` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["email"]], "s");
if(count($query_rows) > 0){
    $logger->warning('[REGISTER] e-mail already in use.', ['username' => $_POST["username"], 'email' => $_POST["email"]]);
    redirect_with_error("register", "e-mail already in use");
}

//TODO: Send verification mail (as a util)
//$verification_token = send_verification_mail($_POST["email"]);

//TODO: Remove later once mail verification has been implemented
$verification_token = bin2hex(random_bytes(32));

//Insert user into the database
$query = "INSERT INTO `users` (`username`,`first_name`,`last_name`,`email`,`password`,`is_verified`,".
                      "`verif_token`) VALUES".
         "(?, ?, ?, ?, ?, ?, ?)";
$query_result = $db->exec_query("INSERT", $query, [$_POST["username"],$_POST["first_name"],$_POST["last_name"],
                                                   $_POST["email"],password_hash($_POST["password"], PASSWORD_DEFAULT),
                                                   0,$verification_token], "sssssis");

//At this point the registration is done, redirect to the login
redirect_to_page("login");

?>