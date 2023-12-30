<?php
    include 'check_secure.php';
    include 'connection.php';
    
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    $order = ($sort === 'asc') ? 'ASC' : 'DESC';
    $titleSearch = isset($_GET['title']) ? $_GET['title'] : '';
    $authorSearch = isset($_GET['author']) ? $_GET['author'] : '';
    $genreSearch = isset($_GET['genre']) ? $_GET['genre'] : '';
    
    $sql = "SELECT * FROM books WHERE title LIKE '%$titleSearch%' AND author LIKE '%$authorSearch%' AND genre LIKE '%$genreSearch%' ORDER BY title $order";
    $result = $conn->query($sql);
    $totalBooks = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lista książek</title>
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
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-btns {
            display: flex;
        }
        .action-btns button {
            margin-right: 5px;
        }
        .centered-buttons {
            text-align: center;
            margin-bottom: 10px; 
        }
    </style>
</head>
<body>
    <h1>Moja Biblioteka</h1>
    <div style="text-align: center; padding: 10px; margin-top: 20px; ">
    <div style="display: inline-block; border: 1px solid #ddd; padding: 10px;">
        <h4 style="color: #333; margin: 0; cursor: pointer;">
            Aktualnie w bibliotece znajduje się
            <span style="font-weight: bold; color: #000;">
                <span style="color: black;" onmouseover="this.style.color='red'" onmouseout="this.style.color='black'">
                    <?php echo isset($totalBooks) ? $totalBooks : 0; ?>
                </span>
            </span> pozycji.
        </h4>
    </div>
</div>
<br>
    <form action="" method="GET">
        <div class="centered-buttons">
            <input type="text" name="title" placeholder="Tytuł">
            <input type="text" name="author" placeholder="Autor">
            <input type="text" name="genre" placeholder="Gatunek">
            <button type="submit" name="search" class="btn btn-primary">Szukaj</button>
            <button type="submit" name="sort" value="asc" class="btn btn-primary">Sortuj A-Z</button>
            
        
    </form>
    <form action="index.php">
        <button type="submit" class="btn btn-secondary" style="margin-top: 10px;">Powrót</button>
    </form>
    </div>
    <table>
        <tr>
            <th>Tytuł</th>
            <th>Autor</th>
            <th>Gatunek</th>
            <th>Akcje</th>
        </tr>
        <?php
            include 'connection.php';
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["author"] . "</td>";
                    echo "<td>" . $row["genre"] . "</td>";
                    echo "<td class='action-btns'>
                            <form action='edit_book.php' method='GET'>
                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                <button type='submit' name='edit' class='btn btn-success'>Edytuj</button>
                            </form>
                            <form action='delete_book.php' method='POST'>
                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                <button type='submit' name='delete' class='btn btn-danger'>Usuń</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Brak książek w bibliotece.</td></tr>";
            }
            $conn->close();
        ?>
    </table>
    <div class="centered-buttons">
    <form action="index.php">
        <button type="submit" class="btn btn-secondary">Powrót</button>
    </form>
    </div>
</body>
</html>
