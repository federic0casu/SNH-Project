<?php
//UTIL IMPORTS
include_once 'csrf.php';
include_once 'db_manager.php';
include_once 'db_utils.php';
include_once 'logger.php';
include_once 'navigation.php';
include_once 'validation.php';
include_once 'mail_utils.php';

//CONSTANTS
$lockout_threshold = 3;

$regexes = [
    'firstname' => "[\\-'A-Z a-zÀ-ÿ]+",
    'lastname' => "[\\-'A-Z a-zÀ-ÿ]+",
    'address' => "[\\-'A-Z a-zÀ-ÿ0-9.,]+",
    'city' => "[\\-'A-Z a-zÀ-ÿ.]+",
    'postalcode' => "\d+",
    'country' => "[\\-'A-Z a-z]+",
    'cardnumber' => "\b\d{4}[\\- ]?\d{4}[\\- ]?\d{4}[\\- ]?\d{4}\b",
    'cvv' => "\d{3}",
];
?>