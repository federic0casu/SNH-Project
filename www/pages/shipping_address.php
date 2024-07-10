<?php
include_once '../php/utils/config_and_import.php';

$user_id = get_logged_user_id();
if ($user_id < 0 || !isset($_SESSION['order_id'])) {
    redirect_with_error(
        "error", 
        "To continue, please make sure you're logged in and you have inserted a valid payment method."
    );
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
    Logger::getInstance()->warning('[CHECKOUT] Failed attempt: User tried to insert a shipping address with an empty cart.', ['userid' => $user_id]);
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
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <button class="home-button" onclick="location.href='../../index.php';">Home</button>
            <form action="../php/utils/logout.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                <input class="logout-button" type="submit" value="Logout">
            </form>
            <button class="cart-button" onclick="location.href='shopping_cart.php';">Cart</button> 
        </div>
    </header>
    <div class="content">
        <div class="payment-form">
            <h2>Shipping Address</h2>
            <form action="../php/process_shipping.php" method="POST">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="1" required pattern="<?php echo $regexes['address']; ?>"></textarea><br>

                <label for="city">City:</label>
                <textarea id="city" name="city" rows="1" required placeholder='Pisa' pattern="<?php echo $regexes['city']; ?>"></textarea><br>

                <label for="postal_code">Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" required placeholder='12345' pattern="<?php echo $regexes['postalcode']; ?>"><br>

                <label for="country">Country:</label>
                <textarea id="country" name="country" rows="1" required placeholder='Italy' pattern="<?php echo $regexes['country']; ?>"></textarea><br>

                <input type="submit" value="Order summary">
            </form>
        </div>
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
        </div>
    </div>
    <br>
    <section class="about-us">
        <h2>About Us</h2>
        <p>Book Emporium is dedicated to providing a curated selection of high-quality books across various genres. We believe in the power of literature to inspire, educate, and entertain. Explore our collection and find your next favorite read!</p>
    </section>
    <footer>
        <p>&copy; 2023 Book Emporium. All rights reserved.</p>
    </footer>
</body>
</html>
