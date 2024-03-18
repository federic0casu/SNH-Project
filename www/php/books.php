<?php
include_once 'utils/db_manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = DBManager::get_instance();

    $query = "SELECT * FROM `books` WHERE `book_title` LIKE ? OR `book_author` LIKE ? ORDER BY `book_title` LIMIT 9";
    $query_rows = $db->exec_query("SELECT", $query, ["%".$_POST["search_query_title"]."%",
                                                     "%".$_POST["search_query_author"]."%"], "ss");

    $books = array();

    if (count($query_rows) > 0) {
        foreach($query_rows as $book) {
            $books[] = array(
                'isbn'   => $book['isbn'],
                'title'  => $book['book_title'],
                'author' => $book['book_author'],
                'image'  => $book['image_url_M']
            );
        }
    } else {
        $books = array('message' => 'No books found.');
    }

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($books);   
}
?>
