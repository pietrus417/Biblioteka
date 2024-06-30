<?php
include 'check_secure.php';
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];

    $book_sql = "SELECT * FROM books WHERE id = ?";
    $book_stmt = $conn->prepare($book_sql);
    $book_stmt->bind_param("i", $id);
    $book_stmt->execute();
    $book_result = $book_stmt->get_result();

    if ($book_result->num_rows > 0) {
        $book = $book_result->fetch_assoc();
    }

    $notes_sql = "SELECT * FROM notes WHERE book_id = ? ORDER BY created_at DESC";
    $notes_stmt = $conn->prepare($notes_sql);
    $notes_stmt->bind_param("i", $id);
    $notes_stmt->execute();
    $notes_result = $notes_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notatki i Oceny</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link type="image/png" sizes="32x32" rel="icon" href="icon/icons8-library-32.png">
    <link rel="icon" type="image/png" sizes="72x72" href="icon/icons8-library-72.png">
    <link rel="apple-touch-icon" type="image/png" sizes="icon/57x57" href="icons8-library-57.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .top-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            z-index: 999;
        }
        h1 {
            text-align: center;
            margin-top: 80px;
        }

        table {
            width: auto;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        tr {
            cursor: default;
        }
        tr:hover {
            background-color: #f8d8f7;
            cursor: default;
        }

        .centered-buttons {
            text-align: center;
            margin: 10px;
        }
    </style>
</head>

<body>
<nav class="top-menu">
<?php include 'navbar.php'; ?>
</nav>
    <h1>Notatki i Oceny</h1>
    <div style="text-align: center; padding: 10px; margin-top: 20px;">
        <div style="display: inline-block; border: 1px solid #ddd; padding: 10px;">
            <h4 style="color: #333; margin: 0;">
                Notatki dla książki:
                <span style="font-weight: bold; color: #000;"><?php echo $book["title"]; ?></span>
            </h4>
        </div>
    </div>
    <br>
    <table>
        <tr>
            <th>Notatka</th>
            <th>Data</th>
        </tr>
        <?php
if ($notes_result->num_rows > 0) {
    while ($note = $notes_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $note["note"] . "</td>";
        echo "<td>" . $note["created_at"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>Brak notatek dla tej książki.</td></tr>";
}
$conn->close();
?>
    </table>
    <div class="centered-buttons">
        <form action="notes">
            <button type="submit" class="btn btn-secondary">Powrót</button>
        </form>
    </div>
</body>

</html>