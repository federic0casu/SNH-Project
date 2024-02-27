<?php
// Include the navigation util
include_once 'navigation.php';

//Check if a specified form field has been supplied
//and is not empty in the POST array.
function check_field_presence($fieldname){
    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
        return false;
    }
    return true;
}

//Check if a specified form field has been supplied
//and is not empty in the POST array. If it is,
//redirect to the specified page with an error message.
function check_field_and_redirect_error($page_name, $fieldname){
    //Check if a field is filled. If it isn't, redirect to the
    //specified page with an error message.
    if(!check_field_presence($fieldname)){
        $error_message = "Missing ".str_replace("_", " ", $fieldname)." field";
        redirect_with_error($page_name, $error_message);
    }
}

//Check that the supplied email address is a string and is in a
//email address format.
function is_valid_email_address($addr){
    return is_string($addr) && filter_var($addr, FILTER_VALIDATE_EMAIL);
}

//Check if a password abides the rules. The functions returns an 
//error string. If empty, the password passed all checks.
function validate_password($pass){
    if (strlen($pass) < 8 || strlen($pass) > 16) {
        return "Password length should be between 8 characters and 16 characters";
    }
    if (!preg_match("/\d/", $pass)) {
        return "Password should contain at least one digit";
    }
    if (!preg_match("/[A-Z]/", $pass)) {
        return "Password should contain at least one Capital Letter";
    }
    if (!preg_match("/[a-z]/", $pass)) {
        return "Password should contain at least one small Letter";
    }
    if (!preg_match("/\W/", $pass)) {
        return "Password should contain at least one special character";
    }
    if (preg_match("/\s/", $pass)) {
        return "Password should not contain any white space";
    }

    return "";
}

?>