<?php
include_once '../php/utils/config_and_import.php';

// Check that the user is logged in
$user_id = get_logged_user_id();
if ($user_id < 0) {
    $_SESSION['checkout'] = 1;
    redirect_with_error("login", "To continue, please log in prior to proceeding to checkout.");
    exit();
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

// Get the order_id from the session
$order_id = $_SESSION['order_id'];

// Fetch the order details including shipping address
$order = get_order_details($user_id, $order_id);

// Check if there was an error retrieving the order details
if ($order === NULL) {
    Logger::getInstance()->error('[ERROR] Failed to fetch order details.', ['userid' => $user_id, 'order_id' => $order_id]);
    redirect_with_error("error", "Failed to fetch order details. Please try again later.");
    exit();
}

// Check if the order details are empty (not found)
if (empty($order)) {
    Logger::getInstance()->error('[ORDER] Order details not found.', ['userid' => $user_id, 'order_id' => $order_id]);
    redirect_with_error("error", "Order details not found. Please verify your order information.");
    exit();
}

// Fetch the cart items
$cart = get_cart($user_id);

// Check if there was an error retrieving the cart
if ($cart === NULL) {
    Logger::getInstance()->error('[ERROR] Failed to retrieve cart for user.', ['userid' => $user_id]);
    redirect_with_error("error", "Something went wrong while fetching your cart. Please try again later.");
    exit();
}

// Check if the cart is empty
if (empty($cart)) {
    Logger::getInstance()->warning('[CHECKOUT] Failed attempt: User tried to place an order with an empty cart.', ['userid' => $user_id]);
    redirect_with_error(
        "error", 
        "Your cart is empty. Please add at least one book before proceeding to checkout."
    );
    exit();
}

//Simulate credit card payment
if(!validate_payment($order['card_number'],$order['expiry_date'],$order['cvv'])){
    Logger::getInstance()->warning('[CHECKOUT] Failed attempt: Credit card transaction rejected.', ['userid' => $user_id]);
    redirect_with_error(
        "error", 
        "Your credit card transaction did not complete successfully."
    );
}

// Insert order items into order_items table
if (insert_order_items($cart, $order_id, $user_id)) {
    send_order_summary($user_id, $order_id);
    // Success message or further processing
    echo "Order placed successfully!<br>";
    echo "<a href=\"../index.php\">Go back to home page</a>";
} else {
    // Handle insertion failure
    echo "Failed to insert order items. Please try again.<br>";
    echo "<a href=\"../index.php\">Go back to home page</a>";
}
?>