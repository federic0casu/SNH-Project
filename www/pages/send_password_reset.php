<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Reset</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/register.css">
    </head>
    <body>
        <header class="header">
            <div class="header-left" onclick="location.href='./../index.php';">
                <h1>Book Emporium</h1>
                <p>Your Source for Great Reads</p>
            </div>
        </header>
        <div class="container">
            <h2>Password Reset</h2>
            <div class="error-message" id="error-message" style="display:none;">
                An error was encountered.
            </div>
            <br>
            <form action="../php/send_password_reset.php" method="post">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br><br>
    
                <input type="submit" value="Reset">
            </form>
        </div>
        <script>
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const error = urlParams.get('error');
            if (error != undefined) {
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-message').innerText = error;
            }
        </script>
    </body>
</html>
