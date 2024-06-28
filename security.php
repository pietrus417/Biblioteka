<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pytanie zabezpieczające</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    </style>
</head>
<body>
    <div class="form-wrapper">
        <h2>Hola hola! Nie tak szybko &#128570;</h2>
        <h4>najpierw odpowiedz na pytanie &#128516;</h4>
        <img src="dog.jpg" alt="Stop" width="250" height="auto">
        <form action="sprawdz_odpowiedz.php" method="post">
            <label for="answer">Jak nazywa się Twój pies?</label>
            <input type="text" id="answer" name="answer" placeholder="">
            <input type="submit" value="Sprawdź">
        </form>
    </div>
</body>
</html>
