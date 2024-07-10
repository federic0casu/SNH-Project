<?php
include_once 'utils/config_and_import.php';

$user_id = get_logged_user_id();
if($user_id < 0) {
    // Set a session variable in order to redirect the user
    // to the checkout page once (s)he successfully authenticates
    $_SESSION['checkout'] = 1;
    Logger::getInstance()->warning('[CHECKOUT] Failed attempt: user not logged in.', ['userid' => $user_id]);
    redirect_with_error("login", "To continue, please log in before proceeding to checkout.");
    exit();
}

// Fetch the user's cart
$cart = get_cart($user_id);

// Check if there was an error retrieving the cart
if ($cart === NULL) {
    Logger::getInstance()->error('[ERROR] Failed to retrieve cart for user.', ['userid' => $user_id]);
    redirect_with_error("error", "Something went wrong while fetching your cart. Please try again later.");
    exit();
}

// Check if the cart is empty
if (empty($cart)) {
    Logger::getInstance()->warning('[CHECKOUT] Failed attempt: User tried to insert a payment method with an empty cart.', ['userid' => $user_id]);
    redirect_with_error(
        "error", 
        "Your cart is empty. Please add at least one book before proceeding to checkout."
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
    'firstname'   => 'first name',
    'lastname'    => 'last name',
    'address'     => 'billing address',
    'city'        => 'city of residence',
    'postal_code' => 'postal code',
    'country'     => 'country',
    'card_number' => 'card number',
    'expiry_date' => 'card expiration date',
    'cvv'         => 'card CVV'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($required_fields as $field => $name) {
        if (!check_field_presence($field)) {
            redirect_with_error(
                "error", 
                "To proceed with your order, please enter your {$name} before continuing to checkout.",
                "checkout.php"
            );
            exit();
        }
    }

    // Sanitize and retrieve form data
    $firstname   = sanitize_input($_POST['firstname']);
    $lastname    = sanitize_input($_POST['lastname']);
    $address     = sanitize_input($_POST['address']);
    $city        = sanitize_input($_POST['city']);
    $postal_code = sanitize_input($_POST['postal_code']);
    $country     = sanitize_input($_POST['country']);
    $card_number = sanitize_input($_POST['card_number']);
    $expiry_date = sanitize_input($_POST['expiry_date']);
    $cvv         = sanitize_input($_POST['cvv']);

    $order = insert_payment_method($user_id, $firstname, $lastname, $address, $city, $postal_code, $country, $card_number, $expiry_date, $cvv);

    $_SESSION['order_id'] = $order;

    if ($order > 0) {
        redirect_to_page("shipping_address");
    } else {
        Logger::getInstance()->warning('[CHECKOUT] Something went wrong while processing payment method.', ['userid' => $user_id, 'order' => $order]);
        redirect_with_error("error", "Something went wrong while processing your payment method. Please try again later.");
    }
} else {
    // Display error message if accessed via GET
    redirect_with_error("error", "");
}
?>
