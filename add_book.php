<?php
include 'connection.php';
include 'check_secure.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $genre = $_POST["genre"];

    // Sprawdzenie, czy pole "genre" jest puste i ewentualne dodanie myślnika
    if (empty($genre)) {
        $genre = '-';
    }

    // Użyj prepared statement
    $stmt = $conn->prepare("INSERT INTO books (title, author, genre) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $author, $genre);

    if ($stmt->execute()) {
        $message = "Książka została dodana do biblioteki.";
    } else {
        $message = "Błąd: " . $stmt->error;
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
                window.location.href = "index.php"; // Przekierowanie po wyświetleniu alertu
            }
        }
    </script>
</head>
<body>
</body>
</html>
