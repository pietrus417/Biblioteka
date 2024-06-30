<?php
include '..\check_secure.php';
include '..\connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row["title"];
        $author = $row["author"];
        $genre = $row["genre"];
        $rating = $row["rating"];
    }

    $note_sql = "SELECT * FROM notes WHERE book_id = ?";
    $note_stmt = $conn->prepare($note_sql);
    $note_stmt->bind_param("i", $id);
    $note_stmt->execute();
    $notes_result = $note_stmt->get_result();

    // Pobranie listy gatunków
    $genre_sql = "SELECT * FROM genres";
    $genres_result = $conn->query($genre_sql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_book"])) {
        $id = $_POST["id"];
        $title = $_POST["title"];
        $author = $_POST["author"];
        $genre = $_POST["genre"];
        $rating = $_POST["rating"];

        $sql = "UPDATE books SET title=?, author=?, genre=?, rating=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $title, $author, $genre, $rating, $id);

        if ($stmt->execute()) {
            header("Location: view");
            exit();
        } else {
            echo "Błąd podczas aktualizacji: " . $conn->error;
        }
    } elseif (isset($_POST["add_note"])) {
        $book_id = $_POST["id"];
        $note = $_POST["note"];

        $note_sql = "INSERT INTO notes (book_id, note) VALUES (?, ?)";
        $note_stmt = $conn->prepare($note_sql);
        $note_stmt->bind_param("is", $book_id, $note);

        if ($note_stmt->execute()) {
            header("Location: edit?id=" . $book_id);
            exit();
        } else {
            echo "Błąd podczas dodawania notatki: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edytuj książkę</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="submit"], textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .rating {
            direction: rtl;
            unicode-bidi: bidi-override;
            display: flex;
            justify-content: center;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 2.5em;
            color: #ddd;
            cursor: pointer;
        }
        .rating input:checked ~ label,
        .rating input:hover ~ label,
        .rating label:hover ~ label {
            color: #f5b301;
        }
    </style>
</head>
<body>
    <h1>Edytuj książkę</h1>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="title">Tytuł:</label>
        <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>
        
        <label for="author">Autor:</label>
        <input type="text" id="author" name="author" value="<?php echo $author; ?>" required>
        
        <label for="genre">Gatunek:</label>
        <select id="genre" name="genre">
            <?php while ($genre_row = $genres_result->fetch_assoc()): ?>
                <option value="<?php echo $genre_row['name']; ?>" <?php if ($genre == $genre_row['name']) echo 'selected'; ?>>
                    <?php echo $genre_row['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <h3 style="text-align: center;">Ocena:</h3>
        <div class="rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php if ($rating == $i) echo 'checked'; ?>>
                <label for="star<?php echo $i; ?>">&#9733;</label>
            <?php endfor; ?>
        </div>
        
        <input type="submit" name="update_book" value="Zapisz zmiany">
    </form>

    <h2 style="text-align: center; margin-top: 20px;" >Notatki</h2>
    <form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Treść notatki</th>
                    <th scope="col">Data dodania</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($note_row = $notes_result->fetch_assoc()): ?>
                    <tr>
                    <td><?php echo nl2br(htmlspecialchars($note_row["note"])); ?></td>
                        <td><?php echo $note_row["created_at"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <textarea name="note" rows="4" placeholder="Dodaj notatkę"></textarea>
    <input type="submit" name="add_note" value="Dodaj notatkę" class="btn btn-primary">
</form>

    <script>
        ClassicEditor
            .create(document.querySelector('#note'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>

<?php
$conn->close();
?>
