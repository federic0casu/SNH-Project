<?php
include_once "utils/config_and_import.php";

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

//Check that the required parameters were sent
if (!isset($_POST['reset_token']) || !is_string($_POST['reset_token'])) {
    $logger->warning('[RESET] Attempt to reset a user password without a token');
    redirect_with_error("password_reset", "Something went wrong. Cannot reset password.");
}
check_post_field_array("register", ["password", "confirm_password"]);

//Check that the password has the correct format
$pass_error = check_valid_password($_POST["password"]);
if(!empty($pass_error)){
    $error = $pass_error;
    redirect_to_page("password_reset", "reset_token={$_POST['reset_token']}&error=".urlencode($error));
}

//Check that passwords match
if($_POST["password"] !== $_POST["confirm_password"]){
    $error = "Passwords do not match";
    redirect_to_page("password_reset", "reset_token={$_POST['reset_token']}&error=".urlencode($error));
}

//Check password strength
$pass_strength_warning = check_password_strength($_POST["password"], $_POST);
if(!empty($pass_strength_warning)){
    $error = $pass_strength_warning;
    redirect_to_page("password_reset", "reset_token={$_POST['reset_token']}&error=".urlencode($error));
}

//Get the token data
$reset_token = $_POST['reset_token'];

//Retrieve info from the db
$query = "SELECT * FROM `users` WHERE `reset_token` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$reset_token], "s");

//Check that a user is present and its password needs to be reset
if(count($query_rows) < 1){
    $logger->warning('[RESET] Token is not associated with a user.', ['reset_token' => $_GET["reset_token"]]);
    $error = "Something went wrong. Cannot reset password.";
    redirect_to_page("password_reset", "reset_token={$_POST['reset_token']}&error=".urlencode($error));
}
if(count($query_rows) > 1){
    $logger->warning('[RESET] Multiple users have the same token.', ['reset_token' => $_GET["reset_token"]]);
    $error = "Something went wrong. Cannot reset password.";
    redirect_to_page("password_reset", "reset_token={$_POST['reset_token']}&error=".urlencode($error));
}

$user = $query_rows[0];

//Check that the reset hasn't expired
if(time() > strtotime($user['reset_valid_until'])){
    $logger->warning('[RESET] Attempting to use an expired reset token.', ['reset_token' => $_GET["reset_token"],
                                                                           'user' => $user["id"]]);
    $error = "Reset token expired";
    redirect_to_page("password_reset", "reset_token={$_POST['reset_token']}&error=".urlencode($error));
}

//If we got here, token is valid and password matches, update the user
$query = "UPDATE `users` SET `password` = ? , `reset_token` = NULL, `reset_valid_until` = NULL WHERE `id` = ?";
$query_rows = $db->exec_query("UPDATE", $query, [password_hash($_POST["password"], PASSWORD_DEFAULT),
                                                 $user["id"]], "si");

//We are done
redirect_to_index();

?>