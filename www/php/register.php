<?php
// Include the database connection
include_once 'db_connect.php';
// Include the validation util
include_once 'utils/validation.php';

//Check that the user isn't already logged in
//TODO

//Check that all needed data was supplied
check_field_and_redirect_error("register", "first_name");
check_field_and_redirect_error("register", "last_name");
check_field_and_redirect_error("register", "username");
check_field_and_redirect_error("register", "email");
check_field_and_redirect_error("register", "password");
check_field_and_redirect_error("register", "confirm_password");

?>