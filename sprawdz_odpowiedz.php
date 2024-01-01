<?php
session_start();

// Sprawdź, czy została wysłana metoda POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy pole odpowiedzi nie jest puste
    if (!empty($_POST['answer'])) {
        // Zabezpiecz odpowiedź przed atakami XSS i SQL Injection
        $answer = htmlspecialchars($_POST['answer']);

        // Sprawdź, czy odpowiedź jest poprawna (tutaj należy ustawić oczekiwaną odpowiedź)
        $expectedAnswer = 'Czika'; // Przykładowa oczekiwana odpowiedź

        if ($answer === $expectedAnswer) {
            // Ustaw zmienne sesji, aby potwierdzić poprawną odpowiedź
            $_SESSION['valid_answer'] = true;
            $_SESSION['valid_until'] = time() + (24 * 60 * 60); // Ustal czas ważności sesji

            // Sprawdź, czy wcześniej zapisano żądaną stronę w zmiennej sesji
            if (isset($_SESSION['desired_page'])) {
                $desiredPage = $_SESSION['desired_page'];
                // Przekieruj użytkownika na żądaną stronę
                header("Location: $desiredPage");
                exit;
            } else {
                // Jeśli nie ma wcześniejszej strony, przekieruj na stronę domową
                header('Location: home');
                exit;
            }
        } else {
            // Jeśli odpowiedź jest nieprawidłowa, przekieruj z powrotem na stronę z pytaniem
            header('Location: restricted_area');
            exit;
        }
    } else {
        // Jeśli pole odpowiedzi jest puste, przekieruj z powrotem na stronę z pytaniem
        header('Location: restricted_area');
        exit;
    }
} else {
    // Jeśli próba dostępu bezpośrednio do tego pliku, przekieruj na stronę z pytaniem
    header('Location: restricted_area');
    exit;
}
?>
