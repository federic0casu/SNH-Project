<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Emporium - Your Source for Great Reads</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            border: 1px solid #ddd;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Book Search</h1>
    <form method="post" action="">
        <label for="search_query_title">Search by Title:</label>
        <input type="text" name="search_query_title" id="search_query_title">
        <label for="search_query_author">Search by Author:</label>
        <input type="text" name="search_query_author" id="search_query_author">
        <input type="submit" value="Search">
    </form>
    <?php
    include '../php/db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $sql = "";
        $stmt = "";

        if (isset($_POST["search_query_title"]) and isset($_POST["search_query_author"])) {
            $sql = "SELECT * FROM `books` as B WHERE B.book_title LIKE ? OR B.book_author LIKE ?";
            // Using prepared statement to prevent SQL injection
            $stmt = mysqli_prepare($conn, $sql);
            $search_query_title = "%" . $_POST["search_query_title"] . "%";
            $search_query_author = "%" . $_POST["search_query_author"] . "%";
            mysqli_stmt_bind_param($stmt, "ss", $search_query_title, $search_query_author);
        } else if (isset($_POST["search_query_title"]) and !isset($_POST["search_query_author"])) {
            $sql = "SELECT * FROM `books` as B WHERE B.book_title LIKE ?";
            $stmt = mysqli_prepare($conn, $sql);
            $search_query_title = "%" . $_POST["search_query_title"] . "%";
            mysqli_stmt_bind_param($stmt, "s", $search_query_title);
        } else if (!isset($_POST["search_query_title"]) and isset($_POST["search_query_author"])) {
            $sql = "SELECT * FROM `books` as B WHERE B.book_author LIKE ?";
            $stmt = mysqli_prepare($conn, $sql);
            $search_query_author = "%" . $_POST["search_query_author"] . "%";
            mysqli_stmt_bind_param($stmt, "s", $search_query_author);
        } else {
            $sql = "SELECT * FROM `books` LIMIT 5";
            $stmt = mysqli_prepare($conn, $sql);
        }
        

        // Execute the statement
        mysqli_stmt_execute($stmt);
    
        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['book_title']} by {$row['book_author']} (Published: {$row['year_of_publication']})</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No books found.</p>";
        }
    }
    // Close the connection
    mysqli_close($conn);
    ?>
</body>
</html>
