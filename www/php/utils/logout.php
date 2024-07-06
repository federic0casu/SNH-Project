<?php

include_once 'config_and_import.php';

//Check that the user is logged in
$user_id = get_logged_user_id();
if($user_id < 0){
    redirect_to_index();
}

//Get Logger instance 
$logger = Logger::getInstance();

//Guard against CSRF attacks
if(!isset($_POST["csrf_token"]) || !is_string($_POST["csrf_token"])){
    $logger->warning('[LOGOUT] Logout called without a CSRF token.');
    redirect_to_index();
}

if(!verify_and_regenerate_csrf_token($_POST["csrf_token"])){
    $logger->warning('[LOGOUT] CSRF tokens do not match.', 
                     ['form_token' => $_POST["csrf_token"]]);
    redirect_to_index();
}

//Logout the user
logout_by_token($_COOKIE["user_login"]);

//Redirect to home
redirect_to_index();

?>