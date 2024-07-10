<?php
include_once "utils/config_and_import.php";

//Get Logger instance 
$logger = Logger::getInstance();

// Get user id
$user_id = get_logged_user_id();

// If user id is less than 0 ==> user not logged in
if ($user_id < 0) {
    // Check if anonymous user cookie is not set
    if (!isset($_COOKIE['anonymous_user'])) {
        // Create anonymous session and set user id
        $user_id = create_anonymous_session();
    } else {
        // Get anonymous user id
        $user_id = get_anonymous_user_id();
        
        // If anonymous user id is still less than 0 ==> error
        if ($user_id < 0) {
            // Redirect to error page
            redirect_to_page("error");
        }
    }
}

$isbn   = $_POST['isbn'];
$action = intval($_POST['action']);

if (!($_SERVER['REQUEST_METHOD'] === 'POST') || !isset($isbn) || !isset($action)) {
    redirect_to_page("error");
}

//Guard against CSRF attacks
if(!isset($_POST["csrf_token"]) || !is_string($_POST["csrf_token"])){
    $logger->warning('[UPDATE_CART] UPDATE_CART called without a CSRF token.');
    redirect_to_index();
}

if(!verify_and_regenerate_csrf_token($_POST["csrf_token"])){
    $logger->warning('[UPDATE_CART] CSRF tokens do not match.', 
                     ['form_token' => $_POST["csrf_token"]]);
    redirect_to_index();
}

$db = DBManager::get_instance();
$query = "SELECT * FROM `books` WHERE `isbn` = ?";
$books = $db->exec_query("SELECT", $query, [$isbn], "s");

// Check if the book exists
if(count($books) == 0) {
    redirect_to_page("error");
}

$book  = $books[0];
$title  = $book['book_title'];
$author = $book['book_author'];
$image  = $book['image_url_M'];

$query = "SELECT quantity FROM `shopping_carts` WHERE `isbn` = ? AND `user_id` = ?";
$quantity = $db->exec_query("SELECT", $query, [$isbn, $user_id], "si");

if ($action === 1) {
    // Check if the shopping cart contains the selected book
    if (count($quantity) == 0) {
        $query = "INSERT INTO `shopping_carts` (`user_id`, ".
                    "`isbn`, `book_title`, `book_author`, `price`, ".
                    "`quantity`, `image_url_M`) VALUES (?,?,?,?,?,?,?)";
        $db->exec_query("INSERT", $query, [$user_id, $isbn, $title, $author, 10.0, 1, $image], "isssdis");
    } else {
        // Increase the quantity of the selected book in the shopping cart
        $query = "UPDATE `shopping_carts` SET `quantity` = `quantity` + 1 WHERE `isbn` = ? AND `user_id` = ?";
        $db->exec_query("UPDATE", $query, [$isbn, $user_id], "ii");
    }
} else if ($action === 2) {
    // If the shopping cart doesn't contain the selected book -> do nothing
    if (count($quantity) == 0) {
        // Redirect back to the previous page
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    $quantity = intval($quantity[0]['quantity']);
    if (intval($quantity) === 1) {
        $query = "DELETE FROM `shopping_carts` WHERE `isbn` = ? AND `user_id` = ?";
        $db->exec_query("DELETE", $query, [$isbn, $user_id], "ii");
    } else {
        // Decrease the quantity of the selected book in the shopping cart
        $query = "UPDATE `shopping_carts` SET `quantity` = `quantity` - 1 WHERE `isbn` = ? AND `user_id` = ?";
        $db->exec_query("UPDATE", $query, [$isbn, $user_id], "ii");
    }
} else {
    redirect_to_page("error");
}

// Redirect back to the previous page
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>
