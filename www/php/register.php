<?php
// Include the database connection
include_once 'utils/db_manager.php';
// Include the navigation util
include_once 'utils/navigation.php';
// Include the validation util
include_once 'utils/validation.php';

//Check that the user isn't already logged in
//TODO

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

//Check if the username is already taken
$query = "SELECT * FROM `users` WHERE `username` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["username"]], "s");
if(count($query_rows) > 0){
    //TODO: log this
    redirect_with_error("register", "Username already taken");
}

//Check if the username is already taken
$query = "SELECT * FROM `users` WHERE `email` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["email"]], "s");
if(count($query_rows) > 0){
    //TODO: log this
    redirect_with_error("register", "Email already in use");
}

//TODO: Send verification mail (as a util)
//$verification_token = send_verification_mail($_POST["email"]);

//TODO: Remove later once mail verification has been implemented
$verification_token = bin2hex(random_bytes(32));

//Insert user into the database
$query = "INSERT INTO `users` (`username`,`first_name`,`last_name`,`email`,`password`,`is_verified`,".
                      "`verif_token`, `is_locked`, `failed_attempts`) VALUES".
         "(?, ?, ?, ?, ?, ?, ?, ?, ?)";
$query_result = $db->exec_query("INSERT", $query, [$_POST["username"],$_POST["first_name"],$_POST["last_name"],
                                                   $_POST["email"],password_hash($_POST["password"], PASSWORD_DEFAULT),
                                                   0,$verification_token,0,0], "sssssisii");

//At this point the registration is done, redirect to the login
redirect_to_page("login");

?>