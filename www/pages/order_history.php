<?php
include_once "../php/utils/config_and_import.php";

// Get user id
$user_id = get_logged_user_id();

// If user is not logged in, redirect to login page
if ($user_id < 0) {
    redirect_to_page("login");
}

try {
    $db = DBManager::get_instance();

    // Fetch orders for the logged-in user
    $query_orders = "SELECT * FROM `orders` WHERE `user_id` = ? ORDER BY `created_at` DESC";
    $orders = $db->exec_query("SELECT", $query_orders, [$user_id], "i");

} catch (Exception $e) {
    // Log any database errors
    Logger::getInstance()->error('[ERROR] Trace: Order History Page', ['message' => $e->getMessage()]);
    // Redirect with an error message
    redirect_with_error("error", "Failed to fetch order history. Please try again later.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Book Emporium</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/history.css">
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
            <button class="cart-button" onclick="location.href='shopping_cart.php';">Cart</button>
        </div>
    </header>

    <h2>Order History</h2>
    
    <?php if (empty($orders)): ?>
        <p>No orders found.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h3>Order #<?php echo $order['order_id']; ?> - <?php echo $order['created_at']; ?></h3>
                <p><strong>Status:</strong> 
                    <?php 
                        switch($order['status_id']) {
                            case 0: echo 'Pending'; break;
                            case 1: echo 'Confirmed'; break;
                            case 2: echo 'Delivered'; break;
                            default: echo 'Unknown'; break;
                        }
                    ?>
                </p>
                <p><strong>Total Price:</strong> <?php echo $order['total_price']; ?> $</p>

                <table class="order-items">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Download Ebook</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $query_items = "SELECT oi.*, 
                                                   b.image_url_S AS image_url, 
                                                   b.book_title AS title,
                                                   b.book_author AS author
                                            FROM `order_items` oi
                                            JOIN `books` b ON oi.isbn = b.isbn
                                            WHERE oi.`order_id` = ?";
                            $items = $db->exec_query("SELECT", $query_items, [$order['order_id']], "i");

                            foreach ($items as $item): ?>
                                <tr>
                                    <td class="image">
                                        <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['title']; ?>">
                                    </td>
                                    <td><?php echo $item['title'];    ?></td>
                                    <td><?php echo $item['author'];   ?></td>
                                    <td><?php echo $item['price'];    ?> $</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><a href="../php/download.php?isbn=<?php echo $item['isbn']; ?>">Ebook</a></td>
                                </tr>
                            <?php endforeach;

                        } catch (Exception $e) {
                            // Log any database errors for fetching order items
                            Logger::getInstance()->error('[ERROR] Trace: Order History Page - Fetch Order Items', ['message' => $e->getMessage()]);
                            echo '<tr><td colspan="6">Failed to fetch order items.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Emporium. All rights reserved.</p>
    </footer>
</body>
</html>