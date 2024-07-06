<?php
include_once "db_manager.php";
include_once "logger.php";
include_once "navigation.php";

function get_logged_user_id() : int {

    //Get Logger instance 
    $logger = Logger::getInstance();

    //Check if the user_login cookie is set
    if(!isset($_COOKIE['user_login']) || !is_string($_COOKIE['user_login'])) {
        return -1;
    }

    //Fetch the login info of the user
    $db = DBManager::get_instance();
    $query = "SELECT * FROM `logged_users` WHERE `session_token` = ?";
    $query_rows = $db->exec_query("SELECT", $query, [$_COOKIE['user_login']], "s");

    //Check that there is exactly 1 login_session
    if(count($query_rows) == 0){
        //Attempt to get logged with non-existant session
        $logger->warning('[GET_USER] Attempt to get logged user with non-existant session.',['session_token'=>$_COOKIE['user_login']]);
        return -2;
    }
    if(count($query_rows) > 1){
        //Multiple login session active for the same token,
        //something very bad happened
        $logger->warning('[GET_USER] Multiple login session active for the same token.',['session_token'=>$_COOKIE['user_login']]);
        return -3;
    }

    $login_session = $query_rows[0];

    //Check if the session needs expiring
    if(time() > strtotime($login_session['valid_until'])){
        //Logout the user
        logout_by_token($login_session['session_token']);
        return -4;
    }

    //Return user id
    return $login_session["user_id"];

}

function logout_by_token($session_token) : void {

    $db = DBManager::get_instance();
    //Remove login session
    $query = "DELETE FROM `logged_users` WHERE `session_token` = ?";
    $db->exec_query("DELETE", $query, [$session_token], "s");

    //Remove cookie and expire php session if it was used
    setcookie("user_login", "", time() - 3600, "/", "", true, true);
    session_unset();
    session_destroy();
}

function get_anonymous_user_id() : int {
    if(isset($_COOKIE['anonymous_user']) /*&& is_string($_COOKIE['anonymous_user'])*/) {
        $query_rows = fetch_anonymous_session();

        //Check that there is exactly 1 active anonymous user 
        //associated to the temporary session token
        if(count($query_rows) == 0 || count($query_rows) > 1) {
            //The temporary session token is not valid (it's not a 
            //big deal because an anonymous user cannot do any damage
            //but the event should be reported) 
            $logger = Logger::getInstance();
            $logger->warning('[*] Temporary session token not valid. Some investigations are suggested.', 
                             ['token' => $_COOKIE['anonymous_user']]);
            return -1;
        }

        $anonymous_session = $query_rows[0];

        //Check if the temporary session needs expiring
        if(time() > strtotime($anonymous_session['valid_until'])) {
            expire_anonymous_session_by_token($anonymous_session['session_token']);
            return -1;
        }

        //Return temporary id
        return intval($anonymous_session["id"]);
    } else {
        return -1;
    }
}

function create_anonymous_session() : int {
    //Generate a temporary session cookie
    try {
        $session_id = hexdec(bin2hex(random_bytes(4)));
        $session_token = bin2hex(random_bytes(32));

        $db = DBManager::get_instance();
        $query = "INSERT INTO `anonymous_users` (`id`, `session_token`, `valid_until`) VALUES ".
                "(?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $query_result = $db->exec_query("INSERT", $query, [$session_id, $session_token], "is");

        //Save the cookie
        if (!setcookie("anonymous_user", $session_token, time() + 60 * 60, "/", "", true, true)) {
            throw new Exception('setcookie() failed');
        }

        return $session_id;
    } catch (Exception $e) {
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: create_anonymous_session()', ['message' => $e->getMessage()]);
        redirect_to_page("error");
    }
}

function fetch_anonymous_session() : array {
    //Fetch the temporary session info of the anonymous user
    try {
        $db = DBManager::get_instance();
        $query = "SELECT * FROM `anonymous_users` WHERE `session_token` = ?";
        $query_rows = $db->exec_query("SELECT", $query, [$_COOKIE['anonymous_user']], "s");

        return $query_rows;
    } catch (Exception $e) {
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: fetch_anonymous_session()', ['message' => $e->getMessage()]);
        redirect_to_page("error");
    }
}

function expire_anonymous_session_by_token($session_token) : void {
    //Remove temporary session
    try {
        $db = DBManager::get_instance();
        $query = "DELETE FROM `anonymous_users` WHERE `session_token` = ?";
        $res = $db->exec_query("DELETE", $query, [$session_token], "s");

        if (isset($_COOKIE['anonymous_user'])) {
            //Remove cookie and expire php anonymous session if it was used
            if (!setcookie("anonymous_user", "", time() - 3600, "/", "", true, true)) {
                throw new Exception('setcookie() failed');
            }
        }
    } catch (Exception $e) {
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: expire_anonymous_session_by_token()', ['message' => $e->getMessage()]);
        redirect_to_page("error");
    }
}

?>