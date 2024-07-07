<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Emporium</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        .error-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e74c3c;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="error-container">
        <h1>Oops! Something went wrong.</h1>
        <?php
            // Check if the 'error' parameter is set in the URL
            if (isset($_GET['error'])) {
                // Sanitize the error message to prevent XSS
                $error_message = htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8');
                echo "<p>$error_message</p>";
            } else {
                echo "<p>We're sorry, but an unexpected error occurred. Please try again later.</p>";
            }
        ?>
        <!-- <p>If the problem persists, contact <a href="mailto:support@bookemporium.com">support@bookemporium.com</a>.</p> -->
        <p><a href="../index.php">Go back to home page</a></p>
    </div>

</body>
</html>