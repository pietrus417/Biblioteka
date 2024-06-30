<?php
// Połączenie z bazą danych - zaimportuj odpowiednią konfigurację
include 'connection.php';

// Sprawdź, czy żądanie jest wysłane metodą POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy przekazano identyfikator książki
    if (isset($_POST['id'])) {
        // Zabezpiecz identyfikator książki przed atakami SQL Injection
        $book_id = mysqli_real_escape_string($conn, $_POST['id']);

        // Aktualizuj wartość kolumny "reading" dla odpowiedniego rekordu w bazie danych na 1 (oznaczając jako przeczytaną)
        $sql = "UPDATE books SET reading = 1 WHERE id = '$book_id'";

        if (mysqli_query($conn, $sql)) {
            // Udane zaktualizowanie - przekieruj użytkownika na stronę z listą książek
            header('Location: view');
            exit;
        } else {
            // Jeśli wystąpił problem z zaktualizowaniem rekordu, możesz obsłużyć to odpowiednio
            echo "Wystąpił błąd podczas oznaczania książki jako przeczytanej: " . mysqli_error($conn);
        }
    } else {
        // Jeśli nie przekazano identyfikatora książki, obsłuż to zgodnie z własnymi potrzebami
        echo "Nieprawidłowe żądanie - brak identyfikatora książki.";
    }
} else {
    // Jeśli żądanie nie zostało wysłane metodą POST, przekieruj użytkownika na stronę główną lub odpowiednią stronę
    header('Location: index.php');
    exit;
}

// Zamknij połączenie z bazą danych
mysqli_close($conn);
?>
