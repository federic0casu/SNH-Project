<?php
include_once 'utils/config_and_import.php';

//Get DB instance
$db = DBManager::get_instance();

//Get Logger instance 
$logger = Logger::getInstance();

// Check that the user is logged in
$user_id = get_logged_user_id();
if($user_id < 0) {
    $logger->warning('[DOWNLOAD] Attempt to download while not logged in.');
    redirect_with_error("login", "Please, log in before attempting to download a book.");
}

//Check that the required parameters were sent
if (!isset($_GET['isbn']) || !is_string($_GET['isbn'])) {
    $logger->warning('[DOWNLOAD] Attempt to download a book without the isbn parameter');
    redirect_to_index();
}

//Sanitize isbn to only include numbers
$isbn = preg_replace("/[^0-9]/", "", $_GET['isbn']);

//Retrieve info from the db
$query = "SELECT * 
FROM `order_items`
INNER JOIN `orders`
USING (`order_id`)
WHERE `user_id` = ? AND `isbn` = ?";
$query_rows = $db->exec_query("SELECT", $query, [$user_id, $isbn], "is");

//Check that the user has bought the book
if(count($query_rows) < 1){
    $logger->warning('[DOWNLOAD] User attempt to download a book they did not buy.', ['user_id' => $user_id]);
    redirect_to_index();
}
if(count($query_rows) > 1){
    $logger->warning('[DOWNLOAD] User bought the same book multiple times.', ['user_id' => $user_id]);
    redirect_to_index();
}

//If we got here, user certainly bought the book, let them download it
$filename = "./../books/{$isbn}.pdf";

header("Content-Type:application/pdf");
header("Content-Disposition:attachment;filename=\"{$isbn}.pdf\"");
header('Content-Length: ' . filesize($filename));
readfile($filename);

?>