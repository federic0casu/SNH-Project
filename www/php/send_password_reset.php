<?php
include_once "utils/config_and_import.php";

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

//Check that the required parameters were sent
if (!isset($_POST['username']) || !is_string($_POST['username'])) {
    $logger->warning('[RESET] Attempt to reset a password without supllying a user');
    redirect_with_error("send_password_reset", "You must supply a user.");
}

//Check if the user is registered
$query = "SELECT * FROM `users` WHERE `username` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["username"]], "s");
$is_registered = count($query_rows) == 1;

//Get user data from query result (if user is registered)
$user = $is_registered ? $query_rows[0] : ['id' => NULL, 'email' => NULL];

//Set a reset token for the user
$reset_token = bin2hex(random_bytes(32));
$query = "UPDATE `users` SET `reset_token` = ?, `reset_valid_until` = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE `id` = ?";
$query_rows = $db->exec_query("UPDATE", $query, [$reset_token,
                                                 $user["id"]], "si");

//If user exists, send them a reset mail
if($is_registered){
    send_reset_mail($user["email"], $user["username"], $reset_token);
}

redirect_with_error("send_password_reset", "An email has been sent if user exists.");

?>