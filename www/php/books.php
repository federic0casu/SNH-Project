<?php
include_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "";
    $stmt = "";

    $sql = $conn->prepare("SELECT * FROM `books` as B WHERE B.book_title LIKE ? OR B.book_author LIKE ? ORDER BY B.book_title LIMIT 9");
    $search_query_title = "%" . $_POST["search_query_title"] . "%";
    $search_query_author = "%" . $_POST["search_query_author"] . "%";
    $sql->bind_param("ss", $search_query_title, $search_query_author);

    if ($sql->execute()) {
        $result = $sql->get_result();

        $conn->close();

        $books = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = array(
                    'title' => $row['book_title'],
                    'author' => $row['book_author'],
                    'image' => $row['image_url_M']
                );
            }
        } else {
            $books = array('message' => 'No books found.');
        }

        // Output JSON
        header('Content-Type: application/json');
        echo json_encode($books);   
    } else {
        $conn->close();

        header("Location: ../pages/error.html");
    }
}
?>
