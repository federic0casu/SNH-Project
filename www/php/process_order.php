<?php
include_once '../php/utils/config_and_import.php';

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
        '[CHECKOUT] Invalid Checkout Sequence: User attempted to checkout without having a valid `order_id`.',
        ['user_id' => $user_id]
    );
    redirect_with_error("error", "To continue, please start a valid checkout process.", "checkout.php");
    exit();
}

// Check if the user has skipped to insert a payment method.
// This check may seem redundant because $_SESSION['order_id']
// should only be set if the previous steps (process_payment_method.php and process_shipping.php)
// finished successfully. However, it's a good safety measure to
// ensure the order has a valid payment method.
if (!check_payment($_SESSION['order_id'])) {
    Logger::getInstance()->warning(
        '[CHECKOUT] Invalid Checkout Sequence: User attempted to checkout without providing a valid payment method. Suspected malicious attempt.',
        ['user_id' => $user_id]
    );
    redirect_with_error("error", "To continue, please insert a payment method.", "checkout.php");
    exit();
}

// Check if the user has skipped to insert a billing address.
// This check may seem redundant because $_SESSION['order_id']
// should only be set if the previous steps (process_payment_method.php and process_shipping.php)
// finished successfully. However, it's a good safety measure to
// ensure the order has a valid billing address.
if (!check_billing_address($_SESSION['order_id'])) {
    Logger::getInstance()->warning(
        '[CHECKOUT] Invalid Checkout Sequence: User attempted to checkout without providing a valid billing address. Suspected malicious attempt.',
        ['user_id' => $user_id]
    );
    redirect_with_error("error", "To continue, please insert a payment method and a billing address.", "checkout.php");
    exit();
}

// Check if the user has skipped to insert a shipping address.
// This check may seem redundant because $_SESSION['order_id']
// should only be set if the previous step (process_shipping.php)
// finished successfully. However, it's a good safety measure to
// ensure the order has a valid shipping address.
if (!check_shipping_address($_SESSION['order_id'])) {
    Logger::getInstance()->warning(
        '[CHECKOUT] Invalid Checkout Sequence: User attempted to checkout without providing a valid shipping address. Suspected malicious attempt.',
        ['user_id' => $user_id]
    );
    redirect_with_error("error", "To continue, please insert a shipping address.", "shipping.php");
    exit();
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

// Insert order items into order_items table
if (insert_order_items($cart, $order_id, $user_id)) {
    send_order_summary($user_id, $order_id);
    // Success message or further processing
    echo "<div style='text-align: center; margin-top: 50px;'>
            <h1>Thank You for Your Order!</h1>
            <p>Your order has been placed successfully and is being processed.</p>
            <p>You will receive a confirmation email with the order details shortly.</p>
            <a href='../index.php' style='color: #3498db; text-decoration: none; font-weight: bold;'>Go back to home page</a>
          </div>";
} else {
    // Handle insertion failure
    echo "<div style='text-align: center; margin-top: 50px;'>
            <h1>Oops! Something went wrong.</h1>
            <p>Failed to insert order items. Please try again.</p>
            <a href='../index.php' style='color: #3498db; text-decoration: none; font-weight: bold;'>Go back to home page</a>
          </div>";
}

?>