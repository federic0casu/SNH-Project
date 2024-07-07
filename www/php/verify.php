<?php
include_once "utils/config_and_import.php";

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

//Check that the required parameters were sent
if (!isset($_GET['verification_token']) || !is_string($_GET['verification_token'])) {
    $logger->warning('[VERIFY] Attempt to verify without a token');
    redirect_with_error("login", "Invalid verification token or user already verified");
}

//Get the token data
$verif_token = $_GET['verification_token'];

//Retrieve info from the db
$query = "SELECT * FROM `users` WHERE `verif_token` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$verif_token], "s");

//Check that a user is present and needs to be verified
if(count($query_rows) < 1){
    $logger->warning('[VERIFY] Token is not associated with a user.', ['verification_token' => $_GET["verification_token"]]);
    redirect_with_error("login", "Invalid verification token or user already verified");
}
if(count($query_rows) > 1){
    $logger->warning('[VERIFY] Multiple users have the same token.', ['verification_token' => $_GET["verification_token"]]);
    redirect_with_error("login", "Invalid verification token or user already verified");
}

$user = $query_rows[0];

if($user['is_verified'] == 1){
    $logger->warning('[VERIFY] Verified user still has verification token associated.', ['verification_token' => $_GET["verification_token"]]);
    redirect_with_error("login", "Invalid verification token or user already verified");
}

//Once all the checks have passed, update the user
$query = "UPDATE `users` SET `is_verified` = 1 , `verif_token` = NULL WHERE `id` = ?";
$query_rows = $db->exec_query("UPDATE", $query, [$user["id"]], "s");

//Lastly, send the user to the login page
redirect_to_page("login");

?>