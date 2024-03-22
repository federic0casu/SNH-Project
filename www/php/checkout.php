<?php
include_once 'utils/config_and_import.php';

// Fetches the current session (even if the user is not logged in); 
//some NON-critical data is saved to enhance the user experience.
session_start();

// Check that the user isn't logged in
$user_id = get_logged_user_id();
if($user_id < 0) {
    // Set a session variable in order to redirect the user
    // to the checkout page once (s)he succesfully authenticates
    $_SESSION['checkout'] = 1;

    redirect_with_error("login", "To continue, please log in prior to proceeding to checkout.");
}

$db = DBManager::get_instance();
$query = "SELECT SC.`isbn` AS `isbn`, SC.`book_title` AS `title`,
                 SC.`book_author` AS `author`, SC.`price` AS `price`,
                 SC.`quantity` AS `quantity`, B.`image_url_S` AS `image`
                 FROM `shopping_carts` AS SC INNER JOIN `books` AS B ON SC.`isbn` = B.`isbn`
                 WHERE SC.`user_id` = ?";
$cart = $db->exec_query("SELECT", $query, [$user_id], "i");

if (count($cart) > 0) {
    $order = array();
    foreach($cart as $book) {
        $order[] = array(
            'isbn'     => $book['isbn'],
            'title'    => $book['title'],
            'author'   => $book['author'],
            'price'    => $book['price'],
            'quantity' => $book['quantity'],
            'image'    => $book['image']
        );
    }
}
?>