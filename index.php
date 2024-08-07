<?php
include 'check_secure.php';
include 'connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Pobranie listy gatunków
$genres_sql = "SELECT * FROM genres";
$genres_result = $conn->query($genres_sql);

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Rozpoczęcie sesji tylko jeśli nie jest już aktywna
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answer = isset($_POST['answer']) ? $_POST['answer'] : '';

    if ($answer === 'Czika') {
        $_SESSION['valid_answer'] = true;
        $_SESSION['valid_until'] = time() + (24 * 60 * 60); // 1 dzień od teraz
    } else {
        // Niepoprawna odpowiedź
        echo "<div class='form-wrapper'>
            <h2>Pytanie zabezpieczające</h2>
            <img src='dog.jpg' alt='Piesek' width='150' height='150'>
            <form action='' method='post'>
                <label for='answer'>Jak nazywa się Twój pies?</label>
                <input type='text' id='answer' name='answer' placeholder='Wpisz odpowiedź i idziemy dalej :)'>
                <input type='submit' value='Sprawdź'>
                <p style='color: red;'>Niestety, to nie jest poprawna odpowiedź &#128543; <br>Spróbuj jeszcze raz.</p>
            </form>
        </div>";
        exit();
    }
} else {
    if (!isset($_SESSION['valid_answer']) || $_SESSION['valid_answer'] !== true || time() >= $_SESSION['valid_until']) {
        // Brak poprawnej odpowiedzi lub sesja wygasła
        echo "<div class='form-wrapper'>
            <h2>Pytanie zabezpieczające</h2>
            <img src='dog.jpg' alt='Piesek' width='150' height='150'>
            <br>
            <form action='' method='post'>
                <label for='answer'>Jak nazywa się Twój pies?</label>
                <input type='text' id='answer' name='answer' placeholder='Wpisz odpowiedź i idziemy dalej :)'>
                <input type='submit' value='Sprawdź'>
            </form>
        </div>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link type="image/png" sizes="32x32" rel="icon" href="icon/icons8-library-32.png">
    <link rel="icon" type="image/png" sizes="72x72" href="icon/icons8-library-72.png">
    <link rel="apple-touch-icon" type="image/png" sizes="icon/57x57" href="icons8-library-57.png">
    <meta charset="UTF-8">
    <title>Moja Biblioteka 4.0</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function showChangelog() {
        const changelog = "Aktualna wersja 4.0 - dodane notatki i oceny"; // Tutaj wpisz treść zmian
        alert(changelog);
    }
    </script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url(biblio1.jpg); 
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-wrapper {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
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
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label, input, select {
            margin-bottom: 10px;
        }
        input[type="text"], input[type="submit"], select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            display: block;
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
</head>
<body>
<nav class="top-menu">
    <?php include 'navbar.php'; ?>
</nav>
<div class="form-wrapper">
    <h1>Witaj Molu Książkowy!</h1>
    <h4>Możesz już dodawać książki do swoich zbiorów bibliotecznych online!</h4>
    <!-- formularz -->
    <form action="add" method="post">
    <label for="title">Tytuł:</label>
    <input type="text" id="title" name="title" required>
    
    <label for="author">Autor:</label>
    <input type="text" id="author" name="author" required>
    
    <label for="genre">Gatunek:</label>
    <select id="genre" name="genre">
        <?php while ($genre_row = $genres_result->fetch_assoc()): ?>
            <option value="<?php echo $genre_row['id']; ?>"><?php echo $genre_row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-plus-circle"></i> Dodaj książkę</button>
</form>
    
    <!-- lista książek -->
    <div style="margin-top: 10px;">
        <button class="btn btn-info btn-lg" onclick="showChangelog()"><i class="fas fa-exclamation-circle"></i> Changelog</button>
    </div>
</div>
</body>
</html>
