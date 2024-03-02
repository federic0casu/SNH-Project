<?php
// Include the database connection
include_once 'utils/db_manager.php';
// Include the navigation util
include_once 'utils/navigation.php';
// Include the validation util
include_once 'utils/validation.php';

//Check that the user isn't already logged in
//TODO

//Should we filter data passed form the user?
//TODO

//Check that all needed data was supplied
check_field_and_redirect_error("register", "first_name");
check_field_and_redirect_error("register", "last_name");
check_field_and_redirect_error("register", "username");
check_field_and_redirect_error("register", "email");
check_field_and_redirect_error("register", "password");
check_field_and_redirect_error("register", "confirm_password");

//Check that every form field is a string type
if(!is_string($_POST["first_name"])||!is_string($_POST["last_name"])||!is_string($_POST["username"])||
   !is_string($_POST["email"])||!is_string($_POST["password"])||!is_string($_POST["confirm_password"])){
    redirect_with_error("register", "One or more fields are not strings");
}

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
    redirect_with_error("register", "Username already taken");
}

//Check if the username is already taken
$query = "SELECT * FROM `users` WHERE `email` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["email"]], "s");
if(count($query_rows) > 0){
    redirect_with_error("register", "Email already in use");
}

?>