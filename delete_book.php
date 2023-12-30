<?php
include 'check_secure.php';
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    $sql = "DELETE FROM books WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_books.php");
        exit();
    } else {
        echo "Błąd podczas usuwania: " . $conn->error;
    }
}
?>
