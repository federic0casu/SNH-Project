<?php
include_once 'utils/config_and_import.php';

//Check that the user isn't already logged in
$user_id = get_logged_user_id();
if($user_id > 0){
    redirect_to_index();
}

//Check that all needed data was supplied and is a string
check_post_field_array("login", ["username", "password"]);

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

//Check if the user is registered
$query = "SELECT * FROM `users` WHERE `username` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$_POST["username"]], "s");
$is_registered = count($query_rows) == 1;

//Get user data from query result (if user is registered)
$user = $is_registered ? $query_rows[0] : ['id' => NULL, 'password' => ""];

//Check if the user is timed-out
$query = "SELECT * FROM `wrong_login` WHERE `user_id` = ? AND `created_at` > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
$query_rows = $db->exec_query("SELECT", $query, [$user["id"]], "i");
$is_timed_out = count($query_rows) >= $lockout_threshold;

//Check if the password is correct
$is_password_correct = password_verify($_POST["password"], $user["password"]);

////////////////////////////////////////////////// [START] TO REVIEW /////////////////////////////////////////////////
//Check if the user is registered
//if(!$is_registered) {
//    $logger->warning('[LOGIN] Username does not exist.', ['username' => $_POST["username"]]);
//    redirect_with_error("login", "Invalid username or password.");
//}
//Check if the user is timed-out
//if($is_timed_out){
//    $logger->warning('[LOGIN] Too many recently failed login attempts.', ['username' => $_POST["username"]]);
//    redirect_with_error("login", "Too many recently failed login attempts. Retry after a while.");
//}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// To prevent information leakage leading to username enumeration, the application should never indicate that any
// specific account has been suspended. Rather, it should respond to any series of failed logins, even those using
// an invalid username, with a generic message advising that accounts are suspended if multiple failures occur and
// that the user should try again later. Additionally, if an account is suspended, login attempts should be rejected
// without even checking the credentials. Some applications that have implemented a suspension policy remain vulnerable
// to brute-forcing because they continue to fully process login attempts during the suspension period, and they return
// a subtly (or not so subtly) different message when valid credentials are submitted. This behavior enables an
// effective brute-force attack to proceed at full speed regardless of the suspension policy.
//
//Check if the user is timed-out
if($is_timed_out){
    
    $email = get_email_from_username($_POST["username"]);
    
    if($email == "") {
    	// Log a warning about too many failed attempts from an unregistered user
    	$logger->warning('[LOGIN] Too many recently failed login attempts with an unknown username.', ['username' => $_POST["username"]]);
    } else {
    	// Log a warning about too many failed attempts
    	$logger->warning('[LOGIN] Too many recently failed login attempts.', ['username' => $_POST["username"]]);
    	
    	// Construct the message for the email alert
        $message = "Dear {$_POST['username']},\n\nWe have detected multiple failed login attempts on your ";
        $message .= "account at BookEmporium. If this was not you, please take appropriate actions such as ";
        $message .= "changing your password or contacting support.";
        
        // Send the email notification to the user
        send_mail($email, $_POST["username"], "BookEmporium Alert: Security Notification", $message);
    }

    // Redirect the user with a generic error message
    redirect_with_error("login", "Invalid username or password. Multiple unsuccessful attempts may temporarily restrict access for security reasons.");
}

//Check if the user is registered
if(!$is_registered) {
    $logger->warning('[LOGIN] Username does not exist.', ['username' => $_POST["username"]]);
    redirect_with_error("login", "Invalid username or password. Multiple unsuccessful attempts may temporarily restrict access for security reasons.");
}
//[END] TO REVIEW


//Check the password
if(!$is_password_correct){
    $logger->warning('[LOGIN] Password is not correct.', ['username' => $_POST["username"]]);

    //Log wrong login attempt
    $query = "INSERT INTO `wrong_login` (`user_id`) VALUES (?)";
    $query_result = $db->exec_query("INSERT", $query, [$user["id"]], "i");

    //Redirect user
    redirect_with_error("login", "Invalid username or password. Multiple unsuccessful attempts may temporarily restrict access for security reasons.");
}

//Check if the user is verified. Respond this way only if both username and password
//are correct in order to avoid user enumeration.
if($user["is_verified"] == 0) {
    redirect_with_error("login", "User is not verified. Check your mail for the verification link.");
}

//If we arrived here, all checks have succeeded
//Generate the user login session cookie
$session_token = bin2hex(random_bytes(32));
$query = "INSERT INTO `logged_users` (`user_id`, `session_token`, `valid_until`) VALUES ".
         "(?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY))";
$query_result = $db->exec_query("INSERT", $query, [$user["id"],$session_token], "is");

//Check if the user have an anonymous token
$anonymous_user_id = get_anonymous_user_id();
if ($anonymous_user_id < 0) {
    //The anonymous token is not set:
    //   -> the current user haven't added yet a book to his/her shopping cart
    //   -> we don't have to update the anonymous id (setting it to the newly 
    //      created id) in the shopping cart table 

    //Save the cookie
    setcookie("user_login", $session_token, time() + 7 * 24 * 60 * 60, "/", "", true, true);

    //Redirect to home
    redirect_to_index();
    exit;
}

//Expire current anonymous session
expire_anonymous_session_by_token($_COOKIE['anonymous_user']);

//Save the cookie
setcookie("user_login", $session_token, time() + 7 * 24 * 60 * 60, "/", "", true, true);

//Update user_id in shopping_cart table
$query = "UPDATE `shopping_carts` SET `user_id` = ? WHERE `user_id` = ?";
$db->exec_query("UPDATE", $query, [$user["id"], $anonymous_user_id], "ii");

//Check if the user arrives from the checkout procedure
if(isset($_SESSION['checkout']) && $_SESSION['checkout'] === 1) {
    redirect_to_page("shopping_cart");
}

//Redirect to home
redirect_to_index();
?>
