<?php
include_once 'utils/config_and_import.php';


// Check that the user isn't already logged in
$user_id = get_logged_user_id();
if($user_id > 0) {
    redirect_to_index();
}

// Check that all needed data was supplied and is a string
check_post_field_array("login", ["username", "password"]);


// From https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html:
// Incorrectly implemented error messages in the case of authentication functionality 
// can be used for the purposes of user ID and password enumeration. An application 
// should respond (both HTTP and HTML) in a generic manner. 
// 
// Authentication Responses
// Using any of the authentication mechanisms (login, password reset, or password 
// recovery), an application must respond with a generic error message regardless of
// whether:
//  1. The user ID or password was incorrect.
//  2. The account does not exist.
//  3. The account is locked or disabled.
// 
// Thus, check_valid_password() SHOULD NOT BE USED because it returns a detailed
// error message (obv, check_valid_password MUST BE USED during registration process).
//
// Check that the password has the correct format
// $pass_error = check_valid_password($_POST["password"]);
// if(!empty($pass_error)){
//    redirect_with_error("login", $pass_error);
// }

// Get DB instance
$db = DBManager::get_instance();

// Get Logger instance 
$logger = Logger::getInstance();


// If the user doesn't exist, the application will directly throw an error. 
// Otherwise, when the user exists and (s)he is timed-out, it is apparent 
// that there will be more processing before the application errors out. 
// In return, the response time will be different for the same error, allowing
// the attacker to differentiate between a wrong username and a timed-out user.
// 
// Check if the user is registered
// $query = "SELECT * FROM `users` WHERE `username` = ?";
// $query_rows = $db->exec_query("SELECT", $query, [$_POST["username"]], "s");
// if(count($query_rows) == 0) {
//    // TODO: SECURITY log this
//    redirect_with_error("login", "Username does not exist");
// }
//
// Get user data from query result
// $user = $query_rows[0];
//
// Check if the user is timed-out
// $query = "SELECT * FROM `wrong_login` WHERE `user_id` = ? AND `created_at` > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
// $query_rows = $db->exec_query("SELECT", $query, [$user["id"]], "i");
// if(count($query_rows) >= $lockout_threshold){
//  // TODO: SECURITY log this
//    redirect_with_error("login", "Too many recently failed login attempts. Retry after a while.");
// }
//
// Check the password
// if(!password_verify($_POST["password"], $user["password"])){
//    // TODO: SECURITY log this
//
//    //Log wrong login attempt
//    $query = "INSERT INTO `wrong_login` (`user_id`) VALUES (?)";
//    $query_result = $db->exec_query("INSERT", $query, [$user["id"]], "i");
//
//    //Redirect user
//    redirect_with_error("login", "Password is not correct.");
// }


// This code will go through the same process, allowing the application
// to return in approximately the same response time.

// Query to check if the user is registered
$is_registered = "SELECT * FROM `users` WHERE `username` = ?";
$res_is_registered = $db->exec_query("SELECT", $is_registered, [$_POST["username"]], "s");

// Get user data from query result (if user is registered)
$user = (count($res_is_registered) == 0) ? ['id' => "", 'password' => ""] : $res_is_registered[0];
    
// Query to check if the user is timed-out
$timed_out = "SELECT * FROM `wrong_login` WHERE `user_id` = ? AND `created_at` > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
$res_timed_out = $db->exec_query("SELECT", $timed_out, [$user["id"]], "i");

$check_pswd = password_verify($_POST["password"], $user["password"]);

// Check if the user is registered
if(count($res_is_registered) == 0) {
    $logger->warning('[LOGIN] Username does not exist.', ['username' => $_POST["username"]]);
    redirect_with_error("login", "Something went wrong: Invalid username or password.");
}

// Check if the user is timed-out
if(count($res_timed_out) >= $lockout_threshold){
    $logger->warning('[LOGIN] Too many recently failed login attempts.', ['username' => $_POST["username"]]);
    redirect_with_error("login", "Too many recently failed login attempts. Retry after a while.");
}

// Check if the user is verified
if($user["is_verified"] == 0) {
    redirect_with_error("login", "User is not verified. Check your mail for the verification link.");
}

// Check the password
if(!$check_pswd){
    $logger->warning('[LOGIN] Password is not correct.', ['username' => $_POST["username"]]);

    // Log wrong login attempt
    $query = "INSERT INTO `wrong_login` (`user_id`) VALUES (?)";
    $query_result = $db->exec_query("INSERT", $query, [$user["id"]], "i");

    // Redirect user
    redirect_with_error("login", "Something went wrong: Invalid username or password.");
}

// If we arrived here, all checks have succeeded
// Generate the user login session cookie
$session_token = bin2hex(random_bytes(32));
$query = "INSERT INTO `logged_users` (`user_id`, `session_token`, `valid_until`) VALUES ".
         "(?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY))";
$query_result = $db->exec_query("INSERT", $query, [$user["id"],$session_token], "is");

// Save the cookie
setcookie("user_login", $session_token, time() + 7 * 24 * 60 * 60, "/", "", true, true);

// Redirect to home
redirect_to_index();

?>

