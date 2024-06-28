<?php
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["extract"])) {
        $zip = new ZipArchive;
        $res = $zip->open('install_files.zip');
        if ($res === TRUE) {
            $zip->extractTo('.');
            $zip->close();
            $status .= "Files extracted successfully.<br>";
        } else {
            $status .= "Error extracting files.<br>";
        }
    } else {
        $servername = $_POST["servername"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $dbname = $_POST["dbname"];
        $port = $_POST["port"];

        // Create connection
        $conn = new mysqli($servername, $username, $password, "", $port);

        // Check connection
        if ($conn->connect_error) {
            $status .= "Connection failed: " . $conn->connect_error . "<br>";
        } else {
            // Create database
            $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
            if ($conn->query($sql) === TRUE) {
                $status .= "Database created successfully<br>";
            } else {
                $status .= "Error creating database: " . $conn->error . "<br>";
            }

            // Select the database
            $conn->select_db($dbname);

            // Create books table
            $books_sql = "CREATE TABLE IF NOT EXISTS books (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL,
                genre VARCHAR(255) NOT NULL,
                rating INT NOT NULL,
                reading TINYINT(1) NOT NULL DEFAULT 0
            )";

            if ($conn->query($books_sql) === TRUE) {
                $status .= "Table 'books' created successfully<br>";
            } else {
                $status .= "Error creating table 'books': " . $conn->error . "<br>";
            }

            // Create notes table
            $notes_sql = "CREATE TABLE IF NOT EXISTS notes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                book_id INT NOT NULL,
                note TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
            )";

            if ($conn->query($notes_sql) === TRUE) {
                $status .= "Table 'notes' created successfully<br>";
            } else {
                $status .= "Error creating table 'notes': " . $conn->error . "<br>";
            }

            // Create genres table
            $genres_sql = "CREATE TABLE IF NOT EXISTS genres (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )";

            if ($conn->query($genres_sql) === TRUE) {
                $status .= "Table 'genres' created successfully<br>";
            } else {
                $status .= "Error creating table 'genres': " . $conn->error . "<br>";
            }

            // Insert genres into the genres table
            $genresInsertQuery = "INSERT INTO genres (name) VALUES 
            ('-'),
            ('Fikcja'),
            ('Fantastyka'),
            ('Kryminał'),
            ('Biografia'),
            ('Horror'),
            ('Romans'),
            ('Science Fiction'),
            ('Historyczna'),
            ('Przygodowa'),
            ('Młodzieżowa')";

            if ($conn->query($genresInsertQuery) === TRUE) {
                $status .= "Genres inserted successfully<br>";
            } else {
                $status .= "Error inserting genres: " . $conn->error . "<br>";
            }

            // Save database configuration
            $config_content = "<?php\n";
            $config_content .= "\$servername = '$servername';\n";
            $config_content .= "\$username = '$username';\n";
            $config_content .= "\$password = '" . addslashes($password) . "';\n";
            $config_content .= "\$dbname = '$dbname';\n";
            $config_content .= "\$port = $port;\n\n";
            $config_content .= "\$conn = new mysqli(\$servername, \$username, \$password, \$dbname, \$port);\n\n";
            $config_content .= "if (\$conn->connect_error) {\n";
            $config_content .= "    die('Połączenie nieudane: ' . \$conn->connect_error);\n";
            $config_content .= "}\n\n";
            $config_content .= "?>";
            file_put_contents('connection.php', $config_content);

            $status .= "Configuration saved successfully. Please delete 'install.php' for security reasons.<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instalacja skryptu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        input[type="text"], input[type="password"], input[type="number"], input[type="submit"] {
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
        .status {
            width: 50%;
            margin: 0 auto;
            border: 1px solid #ccc;
            background-color: #e9e9e9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Instalacja skryptu</h1>
    <form action="" method="post">
        <label for="servername">Adres serwera baz danych:</label>
        <input type="text" id="servername" name="servername" value="localhost" required>
        
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password">
        
        <label for="dbname">Nazwa bazy danych:</label>
        <input type="text" id="dbname" name="dbname" required>
        
        <label for="port">Port:</label>
        <input type="number" id="port" name="port" value="3306" required>
        
        <input type="submit" value="Zainstaluj">
    </form>

    <form action="" method="post">
        <input type="hidden" name="extract" value="1">
        <input type="submit" value="Wypakuj pliki">
    </form>

    <?php if (!empty($status)): ?>
    <div class="status">
        <?php echo $status; ?>
    </div>
    <?php endif; ?>
</body>
</html>
