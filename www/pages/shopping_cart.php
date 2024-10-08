<?php
include_once "../php/utils/config_and_import.php";

// Get user id
$user_id = get_logged_user_id();
$is_user_logged = true;

// If user id is less than 0 ==> user not logged in
if ($user_id < 0) {
    $is_user_logged = false;
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

$db = DBManager::get_instance();
$query = "SELECT * FROM `shopping_carts` WHERE `user_id` = ?";
$cart = $db->exec_query("SELECT", $query, [$user_id], "i");

$cart = (is_null($cart)) ? [] : $cart;

$total = 0.0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Emporium</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <header class="header">
        <div class="header-left" onclick="location.href='./../index.php';">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <?php if (!$is_user_logged): ?>
                <button class="login-button" onclick="location.href='login.php';">Login</button>
                <button class="register-button" onclick="location.href='register.php';">Register</button>
            <?php endif; ?>
            <?php if ($is_user_logged): ?>
                <form action="../php/utils/logout.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                    <input class="logout-button" type="submit" value="Logout">
                </form>
                <button class="history-button" onclick="location.href='order_history.php';">Order History</button>
            <?php endif; ?> 
            <button class="books-button" onclick="location.href='books.php';">Books</button>
        </div>
    </header>

    <h2>Shopping Cart</h2>
    <table id="cart">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($cart as $item): ?>
            <tr>
                <td>
                    <div>
                        <img src="<?php echo $item['image_url_M']; ?>" alt="<?php echo $item['book_title']; ?>">
                    </div>
                </td>
                <td> <?php echo $item['price']; ?> $</td>
                <td> <?php echo $item['quantity']; ?></td>
                <td>
                    <form action="../php/update_cart.php" method="post">
                        <input type="hidden" name="isbn" value="<?php echo urlencode($item['isbn']); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                        <input type="hidden" name="action" value="1">
                        <input type="submit" value="+">
                    </form>
                    <form action="../php/update_cart.php" method="post">
                        <input type="hidden" name="isbn" value="<?php echo urlencode($item['isbn']); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                        <input type="hidden" name="action" value="2">
                        <input type="submit" value="-">
                    </form>
                </td>
            </tr>
        <?php $total += $item['price'] * $item['quantity']; ?>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="1" class="total">Total:</td>
                <td id="total"><?php echo $total; ?> $</td>
            </tr>
        </tfoot>
    </table>
    <br>
    <br>
    <button class="checkout-button" onclick="location.href='../pages/checkout.php'">Proceed to Checkout</button>

    <section class="about-us">
        <h2>About Us</h2>
        <p>Book Emporium is dedicated to providing a curated selection of high-quality books across various genres. We believe in the power of literature to inspire, educate, and entertain. Explore our collection and find your next favorite read!</p>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Emporium. All rights reserved.</p>
    </footer>
</body>
</html>
