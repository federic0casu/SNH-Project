<?php
include_once "utils/config_and_import.php";

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

// Check that the user is logged in
$user_id = get_logged_user_id();
if($user_id < 0) {
    $logger->warning('[PASSWORD_CHANGE] Attempt to change password logged in.');
    redirect_with_error("login", "Please, log in before attempting to change your password.");
}

//Guard against CSRF attacks
if(!isset($_POST["csrf_token"]) || !is_string($_POST["csrf_token"])){
    $logger->warning('[PASSWORD_CHANGE] PASSWORD_CHANGE called without a CSRF token.');
    redirect_to_index();
}

if(!verify_and_regenerate_csrf_token($_POST["csrf_token"])){
    $logger->warning('[PASSWORD_CHANGE] CSRF tokens do not match.', 
                     ['form_token' => $_POST["csrf_token"]]);
    redirect_to_index();
}

//Set a reset token for the user
$reset_token = bin2hex(random_bytes(32));
$query = "UPDATE `users` SET `reset_token` = ?, `reset_valid_until` = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE `id` = ?";
$query_rows = $db->exec_query("UPDATE", $query, [$reset_token, $user_id], "si");

//Redirect user to the password change form
redirect_to_page("password_reset", "reset_token={$reset_token}");

?>