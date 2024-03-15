<?php
include_once "config_and_import.php";

//Check that the required parameters were sent
if (!isset($_GET['verif_token']) || !is_string($_GET['verif_token'])) {
    redirect_with_error("login", "Invalid verification token or user already verified");
}

//Get the token data
$verif_token = $_GET['verif_token'];

//Retrieve info from the db
$db = DBManager::get_instance();
$query = "SELECT * FROM `users` WHERE `verif_token` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_GET["verif_token"]], "s");

//Check that a user is present and needs to be verified
if(count($query_rows) != 1){
    redirect_with_error("login", "Invalid verification token or user already verified");
}

$user = $query_rows[0];

if($user['is_verified'] == 1){
    redirect_with_error("login", "Invalid verification token or user already verified");
}

//Once all the checks have passed, update the user
$query = "UPDATE `users` SET `is_verified` = 1 , `verif_token` = NULL WHERE `id` = ?";
$query_rows = $db->exec_query("UPDATE", $query, [$user["id"]], "s");

//Lastly, send the user to the login page
redirect_to_page("login");

?>