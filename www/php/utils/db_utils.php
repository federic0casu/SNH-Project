<?php
include_once "db_manager.php";
include_once "logger.php";
include_once "navigation.php";

function get_logged_user_id() : int {
    //Check if the user_login cookie is set
    if(!isset($_COOKIE['user_login']) || !is_string($_COOKIE['user_login'])) {
        return -1;
    }

    $query_rows = 0;
    try {
        //Fetch the login info of the user
        $db = DBManager::get_instance();
        $query = "SELECT * FROM `logged_users` WHERE `session_token` = ?";
        $query_rows = $db->exec_query("SELECT", $query, [$_COOKIE['user_login']], "s");
    } catch (Exception $e) {
        // Log and handle any errors
        Logger::getInstance()->error('[ERROR] Trace: get_logged_user_id()', ['message' => $e->getMessage()]);
        return -69;
    }

    //Check that there is exactly 1 login_session
    if(count($query_rows) == 0){
        //Attempt to get logged with non-existant session
        Logger::getInstance()->warning('[GET_USER] Attempt to get logged user with non-existant session.', ['session_token'=>$_COOKIE['user_login']]);
        return -2;
    }
    if(count($query_rows) > 1){
        //Multiple login session active for the same token,
        //something very bad happened
        Logger::getInstance()->critical('[GET_USER] Multiple login session active for the same token.', ['session_token'=>$_COOKIE['user_login']]);
        return -3;
    }

    $login_session = $query_rows[0];

    //Check if the session needs expiring
    if(time() > strtotime($login_session['valid_until'])){
        //Logout the user
        logout_by_token($login_session['session_token']);
        return -4;
    }

    //Return user id
    return $login_session["user_id"];
}

function get_email_from_user_id(int $user_id): string {
    try {
        $db = DBManager::get_instance();

        // Query to fetch email
        $query = "SELECT email FROM users WHERE id = ?";
        $result = $db->exec_query("SELECT", $query, [$user_id], "i");

        // Check if result is not empty and contains 'email'
        if (isset($result[0]['email'])) {
            return $result[0]['email'];
        } else {
            throw new Exception('No email found for the given user ID.');
        }
    } catch (Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: get_email_from_user_id()', ['message' => $e->getMessage()]);
        return "";
    }
}

function get_email_from_username(string $username): string {
    try {
        $db = DBManager::get_instance();

        // Query to fetch email
        $query = "SELECT email FROM users WHERE username = ?";
        $result = $db->exec_query("SELECT", $query, [$username], "s");

        // Check if result is not empty and contains 'email'
        if (isset($result[0]['email'])) {
            return $result[0]['email'];
        } else {
            throw new Exception('No email found for the given username.');
        }
    } catch (Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: get_email_from_username()', ['message' => $e->getMessage()]);
        return "";
    }
}

function get_password_from_user_id(int $user_id): string {
    try {
        $db = DBManager::get_instance();

        // Query to fetch email
        $query = "SELECT password FROM users WHERE id = ?";
        $result = $db->exec_query("SELECT", $query, [$user_id], "i");

        // Check if result is not empty and contains 'email'
        if (isset($result[0]['password'])) {
            return $result[0]['password'];
        } else {
            throw new Exception('No password found for the given user ID.');
        }
    } catch (Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: get_password_from_user_id()', ['message' => $e->getMessage()]);
        return "";
    }
}

function get_full_name_from_user_id(int $user_id): string {
    try {
        $db = DBManager::get_instance();

        // Query to fetch email
        $query = "SELECT first_name, last_name FROM users WHERE id = ?";
        $result = $db->exec_query("SELECT", $query, [$user_id], "i");

        // Check if result is not empty and contains 'email'
        if (isset($result[0]['first_name']) && isset($result[0]['last_name'])) {
            return $result[0]['first_name'] . " " . $result[0]['last_name'];
        } else {
            throw new Exception('No name found for the given user ID.');
        }
    } catch (Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: get_email_from_user_id()', ['message' => $e->getMessage()]);
        return "";
    }
}

function logout_by_token($session_token) : void {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        //Remove login session
        $query = "DELETE FROM `logged_users` WHERE `session_token` = ?";
        $db->exec_query("DELETE", $query, [$session_token], "s");

        //Remove cookie and expire php session if it was used
        setcookie("user_login", "", time() - 3600, "/", "", true, true);
        session_unset();
        session_destroy();

        // Commit transaction
        $db->commit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        Logger::getInstance()->error('[ERROR] Trace: logout_by_token()', ['message' => $e->getMessage()]);
    }
}

function get_anonymous_user_id() : int {
    if(isset($_COOKIE['anonymous_user']) /*&& is_string($_COOKIE['anonymous_user'])*/) {
        $query_rows = fetch_anonymous_session();

        //Check that there is exactly 1 active anonymous user 
        //associated to the temporary session token
        if(count($query_rows) == 0 || count($query_rows) > 1) {
            //The temporary session token is not valid (it's not a 
            //big deal because an anonymous user cannot do any damage
            //but the event should be reported) 
            Logger::getInstance()->warning(
                '[*] Temporary session token not valid. Some investigations are suggested.', 
                ['token' => $_COOKIE['anonymous_user']]
            );
            return -1;
        }

        $anonymous_session = $query_rows[0];

        //Check if the temporary session needs expiring
        if(time() > strtotime($anonymous_session['valid_until'])) {
            expire_anonymous_session_by_token($anonymous_session['session_token']);
            return -1;
        }

        //Return temporary id
        return intval($anonymous_session["id"]);
    } else {
        return -1;
    }
}

function create_anonymous_session() : int {
    //Generate a temporary session cookie
    try {
        $session_id = hexdec(bin2hex(random_bytes(4)));
        $session_token = bin2hex(random_bytes(32));

        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        $query = "INSERT INTO `anonymous_users` (`id`, `session_token`, `valid_until`) VALUES ".
                "(?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $query_result = $db->exec_query("INSERT", $query, [$session_id, $session_token], "is");

        // Commit transaction
        $db->commit();

        //Save the cookie
        if (!setcookie("anonymous_user", $session_token, time() + 60 * 60, "/", "", true, true)) {
            throw new Exception('setcookie() failed');
        }

        return $session_id;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        Logger::getInstance()->error('[ERROR] Trace: create_anonymous_session()', ['message' => $e->getMessage()]);
        redirect_to_page("error");
    }
}

function fetch_anonymous_session() : array {
    //Fetch the temporary session info of the anonymous user
    try {
        $db = DBManager::get_instance();
        $query = "SELECT * FROM `anonymous_users` WHERE `session_token` = ?";
        $query_rows = $db->exec_query("SELECT", $query, [$_COOKIE['anonymous_user']], "s");

        return $query_rows;
    } catch (Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: fetch_anonymous_session()', ['message' => $e->getMessage()]);
        redirect_to_page("error");
    }
}

function expire_anonymous_session_by_token($session_token) : void {
    //Remove temporary session
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        $query = "DELETE FROM `anonymous_users` WHERE `session_token` = ?";
        $res = $db->exec_query("DELETE", $query, [$session_token], "s");

        if (isset($_COOKIE['anonymous_user'])) {
            //Remove cookie and expire php anonymous session if it was used
            if (!setcookie("anonymous_user", "", time() - 3600, "/", "", true, true)) {
                throw new Exception('setcookie() failed');
            }
        }

        // Commit transaction
        $db->commit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();
        Logger::getInstance()->error('[ERROR] Trace: expire_anonymous_session_by_token()', ['message' => $e->getMessage()]);
        redirect_to_page("error");
    }
}

function get_cart($user_id) : array {
    try {
        $db = DBManager::get_instance();
        $query = "SELECT SC.`isbn` AS `isbn`, SC.`book_title` AS `title`,
                    SC.`book_author` AS `author`, SC.`price` AS `price`,
                    SC.`quantity` AS `quantity`, B.`image_url_S` AS `image`
                    FROM `shopping_carts` AS SC INNER JOIN `books` AS B ON SC.`isbn` = B.`isbn`
                    WHERE SC.`user_id` = ?";
        $cart = $db->exec_query("SELECT", $query, [$user_id], "i");

        $order = array();
        if (count($cart) > 0) {
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
        return $order;
    } catch (Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: get_cart(user_id)', ['message' => $e->getMessage()]);
        return NULL;
    }
}

function insert_payment_method($user_id, $firstname, $lastname, $address, $city, $postal_code, $country, $card_number, $expiry_date, $cvv) : int {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Check if the address already exists
        $query = "SELECT `address_id` 
                  FROM `addresses` 
                  WHERE `address` = ? AND `city` = ? AND `postal_code` = ? AND `country` = ?";
        $existing_address = $db->exec_query("SELECT", $query, [$address, $city, $postal_code, $country], "ssss");

        if (!empty($existing_address)) {
            // Use the existing address ID
            $billing_address_id = $existing_address[0]['address_id'];
        } else {
            // Insert new shipping address
            $query = "INSERT INTO `addresses` 
                      (`address`, `city`, `postal_code`, `country`) 
                      VALUES (?, ?, ?, ?)";
            $billing_address_id = $db->exec_query("INSERT", $query, [$address, $city, $postal_code, $country], "ssss");
            Logger::getInstance()->info('[CHECKOUT] New address inserted', ['address_id' => $billing_address_id]);
        }

        // Check if the payment method already exists
        $query = "SELECT `payment_id`
                  FROM `payments`
                  WHERE `card_number` = ? AND `expiry_date` = ? AND `cvv` = ? AND `first_name` = ? and `last_name` = ?";
        $existing_payment = $db->exec_query(
            "SELECT",    
            $query, 
            [$card_number, $expiry_date, $cvv, $firstname, $lastname],
            "sssss"
        );

        if (!empty($exisisting_payment)) {
            // Use the existing payment ID
            $payment_id = $existing_payment[0]['payment_id'];
        } else {
            // Insert payment details
            $query = "INSERT INTO `payments` " . 
                     "(`card_number`, `expiry_date`, `cvv`, `first_name`, `last_name`) " .
                     "VALUES (?, ?, ?, ?, ?)";
            $payment_id = $db->exec_query(
                "INSERT",
                $query,
                [$card_number, $expiry_date, $cvv, $firstname, $lastname], 
                "sssss");
                Logger::getInstance()->info('[CHECKOUT] New payment method inserted', ['payment_id' => $payment_id]);
        }

        // Insert order
        $query = "INSERT INTO `orders` " . 
                    "(`user_id`, `billing_address_id`, `payment_id`, `total_price`, `status_id`) " .
                    "VALUES (?, ?, ?, 0, 1)";  // status_id is 1 (pending)
        $order_id = $db->exec_query(
            "INSERT", 
            $query,
            [$user_id, $billing_address_id, $payment_id], 
            "iii"
        );

        // Commit transaction
        $db->commit();

        return $order_id;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        Logger::getInstance()->error('[ERROR] Trace: insert_payment_method()', ['message' => $e->getMessage()]);
        return -1;
    }
}

function check_payment($order_id) : bool {
    try {
        $db = DBManager::get_instance();

        $query = "SELECT p.payment_id FROM orders o RIGHT JOIN payments p ON o.payment_id = p.payment_id WHERE o.order_id = ?";
        $result = $db->exec_query("SELECT", $query, [$order_id], "i");
        
        return !empty($result) && $result[0]['payment_id'] !== NULL;
    } catch(Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: check_payment()', ['message' => $e->getMessage()]);
        return false;
    }
}

function check_billing_address($order_id) : bool {
    try {
        $db = DBManager::get_instance();

        $query = "SELECT a.address_id FROM orders o RIGHT JOIN addresses a ON o.billing_address_id = a.address_id WHERE o.order_id = ?";
        $result = $db->exec_query("SELECT", $query, [$order_id], "i");
        
        return !empty($result) && $result[0]['address_id'] !== NULL;
    } catch(Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: check_billing_address()', ['message' => $e->getMessage()]);
        return false;
    }
}

function check_shipping_address($order_id) : bool {
    try {
        $db = DBManager::get_instance();

        $query = "SELECT a.address_id FROM orders o RIGHT JOIN addresses a ON o.shipping_address_id = a.address_id WHERE o.order_id = ?";
        $result = $db->exec_query("SELECT", $query, [$order_id], "i");
        
        return !empty($result) && $result[0]['address_id'] !== NULL;
    } catch(Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: check_shipping_address()', ['message' => $e->getMessage()]);
        return false;
    }
}

function insert_shipping_address($order_id, $address, $city, $postal_code, $country) {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Check if the address already exists
        $query = "SELECT `address_id` FROM `addresses` 
                  WHERE `address` = ? AND `city` = ? AND `postal_code` = ? AND `country` = ?";
        $existing_address = $db->exec_query("SELECT", $query, [$address, $city, $postal_code, $country], "ssss");

        if (!empty($existing_address)) {
            // Use the existing address ID
            $shipping_address_id = $existing_address[0]['address_id'];
            Logger::getInstance()->info('[CHECKOUT] Existing address found', ['address_id' => $shipping_address_id]);
        } else {
            // Insert new shipping address
            $query = "INSERT INTO `addresses` 
                      (`address`, `city`, `postal_code`, `country`) 
                      VALUES (?, ?, ?, ?)";
            $shipping_address_id = $db->exec_query("INSERT", $query, [$address, $city, $postal_code, $country], "ssss");
            Logger::getInstance()->info('[CHECKOUT] New address inserted', ['address_id' => $shipping_address_id]);
        }

        // Update the order with the shipping address ID
        $query = "UPDATE `orders` SET `shipping_address_id` = ? WHERE `order_id` = ?";
        $result = $db->exec_query("UPDATE", $query, [$shipping_address_id, $order_id], "ii");

        if ($result === false || $result === 0) {
            throw new Exception("Failed to update order with shipping address ID.");
        }

        // Commit transaction
        $db->commit();

        return $_SESSION['order_id'];
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        Logger::getInstance()->error('[ERROR] Trace: insert_shipping_address()', ['message' => $e->getMessage(), 'order_id' => $order_id]);
        redirect_with_error("error", "Something went wrong while processing your shipping address. Please try again later.");
    }
}

function get_order_details($user_id, $order_id) : ?array {
    try {
        $db = DBManager::get_instance();

        $query = "SELECT o.*, 
                         ba.address AS billing_address, ba.city AS billing_city, 
                         ba.postal_code AS billing_postal_code, ba.country AS billing_country,
                         sa.address AS shipping_address, sa.city AS shipping_city,
                         sa.postal_code AS shipping_postal_code, sa.country AS shipping_country,
                         p.card_number, p.expiry_date, p.cvv,
                         p.first_name AS billing_first_name, p.last_name AS billing_last_name,
                         s.status_description
                  FROM `orders` AS o
                  LEFT JOIN `addresses` AS ba ON o.billing_address_id = ba.address_id
                  LEFT JOIN `addresses` AS sa ON o.shipping_address_id = sa.address_id
                  LEFT JOIN `payments` AS p ON o.payment_id = p.payment_id
                  LEFT JOIN `statuses` AS s ON o.status_id = s.status_id
                  WHERE o.order_id = ? AND o.user_id = ?";
                  
        $result = $db->exec_query("SELECT", $query, [$order_id, $user_id], "ii");

        if (empty($result)) {
            return NULL;
        }

        $order = $result[0];

        // Fetch order items
        $query = "SELECT oi.*, b.book_title AS book_title, b.book_author AS book_author, b.isbn AS isbn
                  FROM `order_items` AS oi
                  LEFT JOIN `books` AS b ON oi.isbn = b.isbn
                  WHERE oi.order_id = ?";
         
        $order_items_result = $db->exec_query("SELECT", $query, [$order_id], "i");

        $order['items'] = $order_items_result;

        return $order;
    } catch (Exception $e) {
        Logger::getInstance()->error('[ERROR] Trace: get_order_details()', ['message' => $e->getMessage()]);
        return NULL;
    }
}

function insert_order_items($order_items, $order_id, $user_id) {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Prepare the insert query
        $query = "INSERT INTO `order_items` (`order_id`, `isbn`, `price`, `quantity`) VALUES (?, ?, ?, ?)";

        $total_price = 0;
        // Iterate through each item and execute the insert query
        foreach ($order_items as $item) {
            $db->exec_query("INSERT", $query, [
                $order_id,
                $item['isbn'],
                $item['price'],
                $item['quantity']
            ], "isdi");
            $total_price += $item['price'] * $item['quantity'];
        }

        // Update order status to 'confirmed' (status_id = 2)
        if (!update_order_status($order_id, 2, $total_price))
            throw new Exception('Failed to update order status.');

        // Delete items from user's cart
        if (!delete_items_from_cart($user_id)) {
            throw new Exception('Failed to delete shopping cart.');
        }

        // Commit transaction
        $db->commit();

        // Return true if all operations were successful
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        Logger::getInstance()->error('[ERROR] Trace: insert_order_items()', ['message' => $e->getMessage()]);
        return false;
    }
}

function update_order_status($order_id, $status, $total_price = null) : bool {
    try {
        // Get the database instance
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Prepare the update query
        if ($total_price !== null || $total_price <= 0) {
            $query = "UPDATE `orders` SET `status_id` = ?, `total_price` = ? WHERE `order_id` = ?";
            $db->exec_query("UPDATE", $query, [$status, $total_price, $order_id], "idi");
        } else {
            throw new Exception("total price is not valid (value: " . $total_price . ")");
        }

        // Commit transaction
        $db->commit();

        // Return true indicating successful update
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        // Log any database errors
        Logger::getInstance()->error('[ERROR] Trace: update_order_status()', ['message' => $e->getMessage()]);
        return false;
    }
}

function delete_items_from_cart($user_id) : bool {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Prepare delete query
        $query = "DELETE FROM `shopping_carts` WHERE `user_id` = ?";
        $db->exec_query("DELETE", $query, [$user_id], "i");

        // Commit transaction
        $db->commit();

        // Return true indicating successful update
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        // Log and handle any errors
        Logger::getInstance()->error('[ERROR] Trace: delete_items_from_cart()', ['message' => $e->getMessage()]);
        return false;
    }
}

function get_random_books($limit = 4) : array {
    try {
        // Get the database instance
        $db = DBManager::get_instance();
        
        // Prepare the SQL query to fetch a random sample of books
        $query = "SELECT * FROM books ORDER BY RAND() LIMIT ?";
        
        // Execute the query and fetch the result
        $books = $db->exec_query("SELECT", $query, [$limit], "i");
        
        return $books;
    } catch (Exception $e) {
        // Log and handle any errors
        Logger::getInstance()->error('[ERROR] Trace: get_random_books()', ['message' => $e->getMessage()]);
        return array();
    }
}
?>
