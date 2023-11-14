<?php
// Include the database connection
include 'db_connect.php';

if(isset($_POST['username']) and isset($_POST['password'])) {
    $user = $_POST['username'];
    $pswd = $_POST['password'];
    
    // Using prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE users.username = ? and users.password = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ss", $user, $pswd);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch data from the result.
    if ($row = mysqli_fetch_assoc($result)) {
        session_start();
        $_SESSION['logged_in'] = 1;
        $_SESSION['username'] = $user;

        header("Location: ../index.php");
    } else {
        header("Location: ../pages/login.html?error=true");
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: ../pages/login.html?error=true");
}

// Close the connection
mysqli_close($conn);
?>

