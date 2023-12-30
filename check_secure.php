<?php
session_start(); // Rozpoczęcie sesji

if (!(isset($_SESSION['valid_answer']) && $_SESSION['valid_answer'] === true && time() < $_SESSION['valid_until'])) {
    // Jeśli odpowiedź nie jest zapisana lub sesja wygasła, przekieruj użytkownika na stronę z pytaniem zabezpieczającym
    header("Location: security.php");
    exit(); // Upewnienie się, że żadna treść nie zostanie wygenerowana po przekierowaniu
}
?>