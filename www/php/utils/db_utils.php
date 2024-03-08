<?php
include_once "db_manager.php";

function get_logged_user_id() : int{

    //Check if the user_login cookie is set
    if(!isset($_COOKIE['user_login']) || !is_string($_COOKIE['user_login'])){
        return -1;
    }

    //Fetch the login info of the user
    $db = DBManager::get_instance();
    $query = "SELECT * FROM `logged_users` WHERE `session_token` = ?";
    $query_rows = $db->exec_query("SELECT", $query, [$_COOKIE['user_login']], "s");

    //Check that there is exactly 1 login_session
    if(count($query_rows) == 0){
        //TODO: SECURITY log this
        //Attempt to get logged with non-existant session
        return -2;
    }
    if(count($query_rows) > 1){
        //TODO: SECURITY log this
        //Multiple login session active for the same token,
        //something very bad happened
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

function logout_by_token($session_token) : void{

    $db = DBManager::get_instance();
    //Remove login session
    $query = "DELETE FROM `logged_users` WHERE `session_token` = ?";
    $db->exec_query("DELETE", $query, [$session_token], "s");

    //Remove cookie and expire php session if it was used
    setcookie("user_login", "", time() - 3600, "/", "", true, true);
    session_unset();
    session_destroy();
}

?>