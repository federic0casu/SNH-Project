<?php
// Include the navigation util
include_once 'navigation.php';
// Include the zxcvbn and the composer loader
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use ZxcvbnPhp\Zxcvbn;

// Check if a specified form field has been supplied
// and is not empty in the POST array.
function check_field_presence(string $fieldname) : bool{
    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
        return false;
    }
    return true;
}

// Check if a specified form field has been supplied
// and is not empty in the POST array. If it is,
// redirect to the specified page with an error message.
function check_field_and_redirect_error(string $page_name, string $fieldname) : bool{
    //Check if a field is filled. If it isn't, redirect to the
    //specified page with an error message.
    if(!check_field_presence($fieldname)){
        $error_message = "Missing ".str_replace("_", " ", $fieldname)." field";
        redirect_with_error($page_name, $error_message);
        return false;
    }
    //Check that the supplied field is a string
    if(!is_string($fieldname)){
        $error_message = str_replace("_", " ", $fieldname)." field must be a string";
        redirect_with_error($page_name, $error_message);
        return false;
    } 
    return true;
}

//Wrapper for check_field_and_redirect_error over an array
function check_post_field_array(string $page_name, array $post_fields_array) : bool{
    foreach($post_fields_array as $fieldname){
        if(!check_field_and_redirect_error($page_name, $fieldname)){
            return false;
        }
    }
    return true;
}

//Check that the supplied email address is a string and is in a
//email address format.
function is_valid_email_address(string $addr) : bool{
    return is_string($addr) && filter_var($addr, FILTER_VALIDATE_EMAIL);
}

// Check if a password abides the rules. The function returns an 
// error string. If empty, the password passed all checks.
function check_valid_password(string $pass) : string {
    if (strlen($pass) < 8 || strlen($pass) > 16) {
        return "Password length should be between 8 characters and 16 characters";
    }
    if (!preg_match("/\d/", $pass)) {
        return "Password should contain at least one digit";
    }
    if (!preg_match("/[A-Z]/", $pass)) {
        return "Password should contain at least one uppercase character";
    }
    if (!preg_match("/[a-z]/", $pass)) {
        return "Password should contain at least one lowercase character";
    }
    if (!preg_match("/\W/", $pass)) {
        return "Password should contain at least one special character";
    }
    if (preg_match("/\s/", $pass)) {
        return "Password should not contain any white space";
    }

    return "";
}

// Check the password strength. Returns a warning string if
// the password is too weak, otherwise an empty string.
function check_password_strength(string $pass, array $user_data) : string{
    //Remove passwords from user supplied data
    unset($user_data["password"]);
    unset($user_data["confirm_password"]);
    //Convert to array
    $user_data = array_values($user_data);
    //Check password strength
    $zxcvbn = new Zxcvbn();
    $strength = $zxcvbn->passwordStrength($pass, $user_data);
    //If password is weak, return the issue feedback
    if($strength["score"] <= 2){
        return $strength['feedback']['warning'];
    }
    //Password is sufficiently strong
    return "";
}

?>