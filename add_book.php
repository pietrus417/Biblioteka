<?php
include 'connection.php';
include 'check_secure.php';

$message = '';

// Pobieranie listy gatunków do użycia w formularzu
$genres = [];
$result = $conn->query("SELECT * FROM genres");
while ($row = $result->fetch_assoc()) {
    $genres[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $genre_id = $_POST["genre"]; // Tutaj nadal będziemy używać ID gatunku

    // Pobranie nazwy gatunku na podstawie jego ID
    $genre_name = '';
    foreach ($genres as $genre) {
        if ($genre['id'] == $genre_id) {
            $genre_name = $genre['name'];
            break;
        }
    }

    // Użyj prepared statement
    $stmt = $conn->prepare("INSERT INTO books (title, author, genre) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $author, $genre_name);

    if ($stmt->execute()) {
        $message = "Książka została dodana do biblioteki.";
    } else {
        $message = "Ups, coś poszło nie tak :( " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dodaj książkę</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        window.onload = function() {
            var message = '<?php echo $message; ?>';
            if (message !== '') {
                alert(message);
                window.location.href = "home"; // Przekierowanie po wyświetleniu alertu
            }
        }
    </script>
