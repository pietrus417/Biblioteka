<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Moja Biblioteka 2.0</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url(biblio.jpg); 
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
            margin: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label, input {
            margin-bottom: 10px;
        }
        input[type="text"], input[type="submit"] {
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
            margin-top: 10px;
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
</head>
<body>
<?php
session_start(); // Rozpoczęcie sesji

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answer = isset($_POST['answer']) ? $_POST['answer'] : '';

    if ($answer === 'Czika') {
        $_SESSION['valid_answer'] = true;
        $_SESSION['valid_until'] = time() + (24 * 60 * 60); // 1 dzień od teraz

        // Po udzieleniu poprawnej odpowiedzi pokaż zawartość strony
?>
        <div class="form-wrapper">
            <h1>Witaj Molu Książkowy!</h1>
            <h3>Możesz już dodawać książki do swoich zbiorów bibliotecznych online!</h3>
            <!-- formularz -->
            <form action="add_book.php" method="post">
                <label for="title">Tytuł:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="author">Autor:</label>
                <input type="text" id="author" name="author" required>
                
                <label for="genre">Gatunek:</label>
                <input type="text" id="genre" name="genre">
                
                <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-plus-circle"> Dodaj książkę</i></button>
            </form>
            
            <!-- lista książek -->
            <a href="view_books.php" class="btn btn-warning btn-lg" ><i class="fas fa-book"> Przejdź do mojej biblioteki</i></a>
        </div>
<?php
    } else {
        // Jeśli odpowiedź nie jest poprawna, pokaż ponownie pytanie
?>
        <div class="form-wrapper">
            <h2>Pytanie zabezpieczające</h2>
            <img src="dog.jpg" alt="Piesek" width="150" height="150">
            <form action="" method="post">
                <label for="answer">Jak nazywa się Twój pies?</label>
                <input type="text" id="answer" name="answer" placeholder="Wpisz odpowiedź i idziemy dalej :)">
                <input type="submit" value="Sprawdź">
                <p style="color: red;">Niestety, to nie jest poprawna odpowiedź :( <br>Spróbuj jeszcze raz.</p>
            </form>
        </div>
<?php
    }
} else {
    if (isset($_SESSION['valid_answer']) && $_SESSION['valid_answer'] === true && time() < $_SESSION['valid_until']) {
        // Jeśli odpowiedź jest już zapisana i sesja jest aktywna, pokaż zawartość strony
?>
        <div class="form-wrapper">
        <h1>Witaj ponownie!</h1>
            <h4>Możesz już dodawać książki do swoich zbiorów bibliotecznych online!</h4>
            <!-- formularz -->
            <form action="add_book.php" method="post">
                <label for="title">Tytuł:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="author">Autor:</label>
                <input type="text" id="author" name="author" required>
                
                <label for="genre">Gatunek:</label>
                <input type="text" id="genre" name="genre">
                
                <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-plus-circle"> Dodaj książkę</i></button>
            </form>
            
            <!-- lista książek -->
            <a href="view_books.php" class="btn btn-warning btn-lg" ><i class="fas fa-book"> Przejdź do mojej biblioteki</i></a>
        </div>
<?php
    } else {
        // Jeśli nie ma żadnego żądania POST lub sesja wygasła, pokaż ponownie pytanie
?>
        <div class="form-wrapper">
            <h2>Pytanie zabezpieczające</h2>
            <img src="dog.jpg" alt="Piesek" width="150" height="150">
            <br>
            <form action="" method="post">
                <label for="answer">Jak nazywa się Twój pies?</label>
                <input type="text" id="answer" name="answer" placeholder="Wpisz odpowiedź i idziemy dalej :)">
                <input type="submit" value="Sprawdź">
            </form>
        </div>
<?php
    }
}
?>
</body>
</html>
