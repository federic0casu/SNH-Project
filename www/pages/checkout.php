<?php
include_once '../php/utils/config_and_import.php';

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

$regexes = [
    'firstname' => "[\\-'A-Z a-zÀ-ÿ]+",
    'lastname' => "[\\-'A-Z a-zÀ-ÿ]+",
    'address' => "[\\-'A-Z a-zÀ-ÿ0-9.,]+",
    'city' => "[\\-'A-Z a-zÀ-ÿ.]+",
    'postalcode' => "\d+",
    'country' => "[\\-'A-Z a-z]+",
    'cardnumber' => "\b\d{4}[\\- ]?\d{4}[\\- ]?\d{4}[\\- ]?\d{4}\b",
    'cardholder' => "[\\-'A-Z a-zÀ-ÿ.]+",
    'cvv' => "\d{3}",
];
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
            <button class="login-button" onclick="location.href='login.php';">Login</button> 
            <button class="register-button" onclick="location.href='shopping_cart.php';">Cart</button> 
        </div>
    </header>
    <div class="content">
    <div class="payment-form">
            <h2>Payment method</h2>
            <form action="process_order.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="address">Billing address:</label>
                <textarea id="address" name="address" rows="1" required></textarea><br>

                <label for="card_number">Credit Card Number:</label>
                <input type="text" id="card_number" name="card_number" required><br>

                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required><br>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name    ="cvv" required><br><br>

                <input type="submit" value="Use Payment method">
            </form>
        </div>
        <div id="order-summary">
        <h2>Order Summary</h2>
            <?php
                // Check if the order array is not empty
                if (!empty($order)) {
                    $total = 0;
                    echo '<table id="cart">';
                    echo '<tr><th></th><th>Title</th><th>Author</th><th>Price</th><th>Quantity</th></tr>';
                    foreach ($order as $item) {
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
                } else {
                    // If the order array is empty, display a message
                    echo '<p>No items in the order.</p>';
                }
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
