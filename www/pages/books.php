<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Emporium</title>
    <link rel="stylesheet" href="../css/books.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/books.js"></script>
</head>
<body>

    <header>
        <h1>Book Emporium</h1>
    </header>

    <section>
        <form action="../php/books.php" method="post">
            <label for="search_query_title">Search by Title:</label>
            <input type="text" name="search_query_title" id="search_query_title">
            <label for="search_query_author">Search by Author:</label>
            <input type="text" name="search_query_author" id="search_query_author">
            <input type="submit" value="Search">
        </form>
        <section id="result-section">
            <h2>Results</h2>
        </section>
    </section>

</body>
</html>