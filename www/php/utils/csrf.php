<?php
session_set_cookie_params([
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);

if(session_id() === "") session_start();

// Generate and set a new CSRF token.
function generate_csrf_token(){
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
    return $csrf_token;
}

// Retrieve the csrf token in the session if it exists.
// Otherwise, generate and set a new one.
function generate_or_get_csrf_token(){
    if(!isset($_SESSION['csrf_token'])){
        return generate_csrf_token();
    }
    return $_SESSION['csrf_token'];
}

// Check if the session csrf token matches with the supplied one.
function verify_csrf_token($csrf_token) {
    if(!isset($_SESSION['csrf_token'])){
        return false;
    }
    return $_SESSION['csrf_token'] === $csrf_token;
}

// Check if the csrf token is valid and if it is regen it.
function verify_and_regenerate_csrf_token($csrf_token){
    $is_valid = verify_csrf_token($csrf_token);
    if($is_valid){
        generate_csrf_token();
    }
    return $is_valid;
}

?>