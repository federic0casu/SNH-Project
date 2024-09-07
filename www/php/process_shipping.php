<?php
include_once 'utils/config_and_import.php';

// Check if the user isn't logged in
$user_id = get_logged_user_id();
if ($user_id < 0) {
    // Set a session variable in order to redirect the user
    // to the checkout page once (s)he successfully authenticates
    $_SESSION['checkout'] = 1;
    Logger::getInstance()->warning(
        '[CHECKOUT] Unauthorized Access Attempt: User not logged in. Action: Inserting Shipping Address. Suspected malicious attempt.',
        ['user_id' => $user_id]
    );
    redirect_with_error("login", "To continue, please log in before proceeding to checkout.");
    exit();
}

// Check if the user provides a valid order_id.
if (!isset($_SESSION['order_id'])) {
    Logger::getInstance()->warning(
        '[CHECKOUT] Invalid Checkout Sequence: User attempted to insert shipping address without having a valid `order_id`.',
        ['user_id' => $user_id]
    );
    redirect_with_error("error", "To continue, please start a valid checkout process.", "checkout.php");
    exit();
}

// Check if the user has skipped to insert a payment method.
// This check may seem redundant because $_SESSION['order_id']
// should only be set if the previous step (process_payment_method.php)
// finished successfully. However, it's a good safety measure to
// ensure the order has a valid payment method.
if (!check_payment($_SESSION['order_id'])) {
    Logger::getInstance()->warning(
        '[CHECKOUT] Invalid Checkout Sequence: User attempted to insert shipping address without providing a valid payment method. Suspected malicious attempt.',
        ['user_id' => $user_id]
    );
    redirect_with_error("error", "To continue, please insert a payment method.", "checkout.php");
    exit();
}

// Check if the user has skipped to insert a billing address.
// This check may seem redundant because $_SESSION['order_id']
// should only be set if the previous step (process_payment_method.php)
// finished successfully. However, it's a good safety measure to
// ensure the order has a valid billing address.
if (!check_billing_address($_SESSION['order_id'])) {
    Logger::getInstance()->warning(
        '[CHECKOUT] Invalid Checkout Sequence: User attempted to insert shipping address without providing a valid billing address. Suspected malicious attempt.',
        ['user_id' => $user_id]
    );
    redirect_with_error("error", "To continue, please insert a payment method and a billing address.", "checkout.php");
    exit();
}

$cart = get_cart($user_id);
if (empty($cart)) {
    Logger::getInstance()->warning('[CHECKOUT] Failed attempt: user trying to checkout with an empty cart.', ['userid' => $user_id]);
    redirect_with_error(
        "error", 
        "To proceed with your order, please add at least one book to your cart before continuing to checkout."
    );
    exit();
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
                "To proceed with your order, please enter your {$name} before continuing to checkout.",
                "shipping_address.php"
            );
            exit();
        }
    }

    // Sanitize and retrieve form data
    $address     = sanitize_input($_POST['address']);
    $city        = sanitize_input($_POST['city']);
    $postal_code = sanitize_input($_POST['postal_code']);
    $country     = sanitize_input($_POST['country']);

    $order = insert_shipping_address($_SESSION['order_id'], $address, $city, $postal_code, $country);

    if ($order > 0 && $_SESSION['order_id'] == $order) {
        Logger::getInstance()->debug('[CHECKOUT] User succesfully inserted shipping address.', ['userid' => $user_id, 'order' => $order]);
        redirect_to_page("order");
    } else {
        Logger::getInstance()->warning('[CHECKOUT] Something went wrong while processing shipping address.', ['userid' => $user_id, 'order' => $order]);
        redirect_with_error("error", "Something went wrong while processing your shipping address. Please try again later.");
    }
    exit();
} else {
    // Display error message if accessed via GET
    redirect_with_error("error", "To proceed with your order, please enter a valid shipping address before continuing to checkout.", "shipping_address.php");
    exit();
}
?>
