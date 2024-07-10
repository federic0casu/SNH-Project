<?php
include_once '../php/utils/config_and_import.php';

// Check that the user is logged in
$user_id = get_logged_user_id();
if ($user_id < 0) {
    $_SESSION['checkout'] = 1;
    redirect_with_error("login", "To continue, please log in prior to proceeding to checkout.");
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

// Fetch the cart items for display
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>
    <header class="header">
        <div class="header-left" onclick="location.href='../index.php';">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <form action="../php/utils/logout.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                <input class="logout-button" type="submit" value="Logout">
            </form>
            <button class="register-button" onclick="location.href='shopping_cart.php';">Cart</button> 
        </div>
    </header>
    <div class="content">
        <div class="left-column">
            <h2>Payment Method</h2>
            <p><strong>First Name:</strong>  <?php echo htmlspecialchars($order['billing_first_name']);  ?></p>
            <p><strong>Last Name:</strong>   <?php echo htmlspecialchars($order['billing_last_name']);   ?></p>
            <p><strong>Address:</strong>     <?php echo htmlspecialchars($order['billing_address']);     ?></p>
            <p><strong>City:</strong>        <?php echo htmlspecialchars($order['billing_city']);        ?></p>
            <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($order['billing_postal_code']); ?></p>
            <p><strong>Country:</strong>     <?php echo htmlspecialchars($order['billing_country']);     ?></p>
            <p><strong>Card Number:</strong> <?php echo htmlspecialchars($order['card_number']);         ?></p>
            <p><strong>Expiry Date:</strong> <?php echo htmlspecialchars($order['expiry_date']);         ?></p>
            <h2>Shipping Address</h2>
            <p><strong>Address:</strong>     <?php echo htmlspecialchars($order['shipping_address']);    ?></p>
            <p><strong>City:</strong>        <?php echo htmlspecialchars($order['shipping_city']);       ?></p>
            <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($order['shipping_postal_code']);?></p>
            <p><strong>Country:</strong>     <?php echo htmlspecialchars($order['shipping_country']);    ?></p>
        </div>
        <div class="right-column">
            <div id="order-summary">
                <h2>Order Summary</h2>
                <?php
                    $total = 0;
                    echo '<table id="cart">';
                    echo '<tr><th></th><th>Title</th><th>Author</th><th>Price</th><th>Quantity</th></tr>';
                    foreach ($cart as $item) {
                        echo '<tr>';
                        echo '<td>';
                        echo '<img src="' . $item['image'] . '" alt="' . $item['title'] .'">';
                        echo '</td>';
                        echo '<td>' . $item['title']    . '</td>';
                        echo '<td>' . $item['author']   . '</td>';
                        echo '<td>' . $item['price']    . ' $</td>';
                        echo '<td>' . $item['quantity'] . '</td>';
                        echo '</tr>';
                        $total += intval($item['price']) * intval($item['quantity']);
                    }
                    echo '<tfoot>';
                    echo '<tr>';
                    echo '<td colspan="3" class="total">Total:</td>';
                    echo '<td id="total">' . $total . ' $</td>';
                    echo '</tr>';
                    echo '</tfoot>';
                    echo '</table>';
                ?>
                <form action="../php/process_order.php" method="POST">
                    <br>
                    <input type="submit" value="<<BUY NOW>>">
                </form>
            </div>
        </div>
    </div>
    <br>
    <section class="about-us">
        <h2>About Us</h2>
        <p>Book Emporium is dedicated to providing a curated selection of high-quality books across various genres. We believe in the power of literature to inspire, educate, and entertain. Explore our collection and find your next favorite read!</p>
    </section>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Emporium. All rights reserved.</p>
    </footer>
</body>
</html>