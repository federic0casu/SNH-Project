<?php
// Include the database connection
include_once 'db_connect.php';
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
if(!is_string($_POST["first_name"])||!is_string($_POST["first_name"])||!is_string($_POST["first_name"])||
   !is_string($_POST["first_name"])||!is_string($_POST["first_name"])||!is_string($_POST["first_name"])){
    redirect_with_error("register", "One or more fields are not strings");
}

//Check that the email has the correct format
if(!is_valid_email_address($_POST["email"])){
    redirect_with_error("register", "Wrong email address format");
}

//Check that the password has the correct format
$pass_error = validate_password($_POST["password"])
if(!empty($pass_error)){
    redirect_with_error("register", $pass_error);
}

?>