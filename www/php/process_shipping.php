<?php
include_once 'utils/config_and_import.php';

// Fetches the current session (even if the user is not logged in); 
//some NON-critical data is saved to enhance the user experience.
session_start();

//Get Logger instance 
$logger = Logger::getInstance();

// Check if the user isn't logged in
$user_id = get_logged_user_id();
if($user_id < 0) {
    // Set a session variable in order to redirect the user
    // to the checkout page once (s)he successfully authenticates
    $_SESSION['checkout'] = 1;
    $logger->warning('[CHECKOUT] Failed attempt: user not logged in.', ['userid' => $user_id]);
    redirect_with_error("login", "To continue, please log in before proceeding to checkout.");
    exit();
}

$cart = get_cart($user_id);

if (empty($cart)) {
    $logger->warning('[CHECKOUT] Failed attempt: user trying to checkout with an empty cart.', ['userid' => $user_id]);
    redirect_with_error(
        "error", 
        "To proceed with your order, please add at least one book to your cart before continuing to checkout."
    );
    exit();
}

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

$required_fields = [
    'address'     => 'shipping address',
    'city'        => 'shipping city',
    'postal_code' => 'postal code',
    'country'     => 'shipping country'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($required_fields as $field => $name) {
        if (!check_field_presence($field)) {
            redirect_with_error(
                "error", 
                "To proceed with your order, please enter your {$name} before continuing to checkout."
            );
            exit();
        }
    }

    // Sanitize and retrieve form data
    $address     = sanitize_input($_POST['address']);
    $city        = sanitize_input($_POST['city']);
    $postal_code = sanitize_input($_POST['postal_code']);
    $country     = sanitize_input($_POST['country']);

    $order = insert_shipping_address($user_id, $order, $address, $city, $postal_code, $country);

    if ($order > 0 && $_SESSION['order_id'] == $order) {
        $logger->debug('[CHECKOUT] User succesfully inserted payment method.', ['userid' => $user_id, 'order' => $order]);
        redirect_to_page("order");
    } else {
        $logger->warning('[CHECKOUT] Something went wrong while processing payment method.', ['userid' => $user_id, 'order' => $order]);
        redirect_with_error("error", "Something went wrong while processing your payment method. Please try again later.");
    }
    exit();
} else {
    // Display error message if accessed via GET
    redirect_with_error("error", "");
    exit();
}
?>
