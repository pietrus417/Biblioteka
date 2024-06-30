<?php

include '../check_secure.php';
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    // Rozpocznij transakcję
    $conn->begin_transaction();

    try {
        // Najpierw usuń powiązane rekordy z tabeli notes
        $sql_notes = "DELETE FROM notes WHERE book_id = $id";
        if ($conn->query($sql_notes) !== TRUE) {
            throw new Exception("Błąd podczas usuwania notatek: " . $conn->error);
        }

        // Następnie usuń rekord z tabeli books
        $sql_books = "DELETE FROM books WHERE id = $id";
        if ($conn->query($sql_books) !== TRUE) {
            throw new Exception("Błąd podczas usuwania książki: " . $conn->error);
        }

        // Jeśli wszystko poszło dobrze, zatwierdź transakcję
        $conn->commit();
        
        // Przekieruj na stronę z listą książek
        header("Location: ../view");
        exit();
    } catch (Exception $e) {
        // Jeśli wystąpił błąd, cofnij transakcję
        $conn->rollback();
        echo "Wystąpił błąd: " . $e->getMessage();
    } finally {
        // Zamknij połączenie z bazą danych
        $conn->close();
    }
}
?>
