<?php
// Include the database connection
include_once 'db_connect.php';

if(isset($_POST['username']) and isset($_POST['password'])) {
    $user = $_POST['username'];
    $pswd = $_POST['password'];

    // Using prepared statement to prevent SQL injection
    $sql = $conn->prepare("SELECT * FROM users WHERE users.username = ? LIMIT 1");
    $sql->bind_param("s", $user);

    // Execute the statement
    $sql->execute();

    // Get the result
    $result = $sql->get_result();

    // Close the connection
    $conn->close();

    // Fetch data from the result
    if ($row = $result->fetch_assoc()) {
        if (password_verify($pswd, $row['password'])) {
            session_start();

            $_SESSION['username'] = array(
                'username'  => $user,
                'firstname' => $row['first_name'],
                'last_name' => $row['last_name'],
                'logged_in' => 1
            );

            header("Location: ../index.php");
        } else
            // The provided password is wrong
            header("Location: ../pages/login.html?error=true");
    } else {
        // The user provides the wrong username
        header("Location: ../pages/login.html?error=true");
    }
} else {
    // Close the connection
    $conn->close();

    header("Location: ../pages/login.html?error=true");
}
?>

