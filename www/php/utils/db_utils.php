<?php
include_once "db_manager.php";
include_once "logger.php";
include_once "navigation.php";

function get_logged_user_id() : int {

    //Check if the user_login cookie is set
    if(!isset($_COOKIE['user_login']) || !is_string($_COOKIE['user_login'])) {
        return -1;
    }

    //Fetch the login info of the user
    $db = DBManager::get_instance();
    $query = "SELECT * FROM `logged_users` WHERE `session_token` = ?";
    $query_rows = $db->exec_query("SELECT", $query, [$_COOKIE['user_login']], "s");

    //Check that there is exactly 1 login_session
    if(count($query_rows) == 0){
        //TODO: SECURITY log this
        //Attempt to get logged with non-existant session
        return -2;
    }
    if(count($query_rows) > 1){
        //TODO: SECURITY log this
        //Multiple login session active for the same token,
        //something very bad happened
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

function logout_by_token($session_token) : void {

    $db = DBManager::get_instance();
    //Remove login session
    $query = "DELETE FROM `logged_users` WHERE `session_token` = ?";
    $db->exec_query("DELETE", $query, [$session_token], "s");

    //Remove cookie and expire php session if it was used
    setcookie("user_login", "", time() - 3600, "/", "", true, true);
    session_unset();
    session_destroy();
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
            $logger = Logger::getInstance();
            $logger->warning('[*] Temporary session token not valid. Some investigations are suggested.', 
                             ['token' => $_COOKIE['anonymous_user']]);
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
        $query = "INSERT INTO `anonymous_users` (`id`, `session_token`, `valid_until`) VALUES ".
                "(?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $query_result = $db->exec_query("INSERT", $query, [$session_id, $session_token], "is");

        //Save the cookie
        if (!setcookie("anonymous_user", $session_token, time() + 60 * 60, "/", "", true, true)) {
            throw new Exception('setcookie() failed');
        }

        return $session_id;
    } catch (Exception $e) {
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: create_anonymous_session()', ['message' => $e->getMessage()]);
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
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: fetch_anonymous_session()', ['message' => $e->getMessage()]);
        redirect_to_page("error");
    }
}

function expire_anonymous_session_by_token($session_token) : void {
    //Remove temporary session
    try {
        $db = DBManager::get_instance();
        $query = "DELETE FROM `anonymous_users` WHERE `session_token` = ?";
        $res = $db->exec_query("DELETE", $query, [$session_token], "s");

        if (isset($_COOKIE['anonymous_user'])) {
            //Remove cookie and expire php anonymous session if it was used
            if (!setcookie("anonymous_user", "", time() - 3600, "/", "", true, true)) {
                throw new Exception('setcookie() failed');
            }
        }
    } catch (Exception $e) {
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: expire_anonymous_session_by_token()', ['message' => $e->getMessage()]);
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
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: get_cart(user_id)', ['message' => $e->getMessage()]);
        return NULL;
    }
}

function insert_payment_method($user_id, $firstname, $lastname, 
        $address, $city, $postal_code, $country, 
        $card_number, $expiry_date, $cvv
) : int {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Prepare and execute query
        $query = "INSERT INTO `orders` (" . 
                    "`user_id`, `billing_first_name`, `billing_last_name`, " . 
                    "`billing_address`, `billing_city`, `billing_postal_code`, `billing_country`, " . 
                    "`card_number`, `expiry_date`, `cvv`) " .
                    " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $order_id = $db->exec_query("INSERT", $query, [
            $user_id, $firstname, $lastname,
            $address, $city, $postal_code, $country,
            $card_number, $expiry_date, $cvv], 
            "isssssssss");

        // Commit transaction
        $db->commit();

        return $order_id;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: insert_payment_method()', ['message' => $e->getMessage()]);
        return -1;
    }
}

function insert_shipping_address($user_id, $order, $address, $city, $postal_code, $country) {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Insert shipping address into orders table
        $query = "UPDATE `orders` SET `shipping_address` = ?, `shippping_city` = ?,
                    `shipping_postal_code` = ?, `shipping_country` = ? WHERE `order_id` = ?";
        $db->exec_query("UPDATE", $query, [
            $address,
            $city,
            $postal_code,
            $country,
            $_SESSION['order_id']
        ], "ssssi");

        // Commit transaction
        $db->commit();

        return $_SESSION['order_id'];
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: insert_shipping_address()', ['message' => $e->getMessage()]);
        redirect_with_error("error", "Something went wrong while processing your shipping address. Please try again later.");
    }
}

// Function to fetch order details
function get_order_details($user_id, $order_id) : array {
    try {
        $db = DBManager::get_instance();

        $query = "SELECT * FROM `orders` WHERE `order_id` = ? AND `user_id` = ?";
        $result = $db->exec_query("SELECT", $query, [$order_id, $user_id], "ii");

        if (!empty($result)) {
            return $result[0]; // Assuming only one order per order_id and user_id combination
        } else {
            return array();
        }
    } catch (Exception $e) {
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: get_order_details()', ['message' => $e->getMessage()]);
        return NULL;
    }
}

//Function to insert order items into the order_items table.
function insert_order_items($order_items, $order_id, $user_id) {
    try {
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Prepare the insert query
        $query = "INSERT INTO `order_items` 
                    (`order_id`, `isbn`, `title`, `author`, `price`, `quantity`, `image_url`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Iterate through each item and execute the insert query
        foreach ($order_items as $item) {
            $db->exec_query("INSERT", $query, [
                $order_id,
                $item['isbn'],
                $item['title'],
                $item['author'],
                $item['price'],
                $item['quantity'],
                $item['image']
            ], "isssdss");
        }

        if (!update_order_status($order_id, 1))
            throw new Exception('Failed to update order status.');

        if (!delete_items_from_cart($user_id))
            throw new Exception('Failed to update shopping cart.');

        // Commit transaction
        $db->commit();

        // Return true if all inserts were successful
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: insert_order_items()', ['message' => $e->getMessage()]);
        return false;
    }
}

//Function to update the status of an order in the orders table.
function update_order_status($order_id, $status) : bool {
    try {
        // Get the database instance
        $db = DBManager::get_instance();

        // Begin transaction
        $db->begin_transaction();

        // Prepare the update query
        $query = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
        $db->exec_query("UPDATE", $query, [$status, $order_id], "ii");

        // Commit transaction
        $db->commit();

        // Return true indicating successful update
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollback();

        // Log any database errors
        $logger = Logger::getInstance();
        $logger->error('[ERROR] Trace: update_order_status()', ['message' => $e->getMessage()]);
        return false;
    }
}

// Function to delete items from shopping cart
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
?>