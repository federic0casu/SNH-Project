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

?>