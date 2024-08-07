<?php
include 'check_secure.php';
include 'connection.php';

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = ($sort === 'asc') ? 'ASC' : 'DESC';
$titleSearch = isset($_GET['title']) ? $_GET['title'] : '';
$authorSearch = isset($_GET['author']) ? $_GET['author'] : '';
$genreSearch = isset($_GET['genre']) ? $_GET['genre'] : '';
$readFilter = isset($_GET['read']) && $_GET['read'] === '1' ? 'AND reading = 1' : '';

$sql = "SELECT * FROM books WHERE title LIKE '%$titleSearch%' AND author LIKE '%$authorSearch%' AND genre LIKE '%$genreSearch%' $readFilter ORDER BY title $order";
$result = $conn->query($sql);
$totalBooks = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Moje Notatki</title>
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

        .action-btns {
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
        }

        .action-btns button {
            margin-right: 5px;
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
    <h1>Moje Notatki</h1>
    <div style="text-align: center; padding: 10px; margin-top: 20px;">
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
            <div style="margin: 30px;">
            <input type="checkbox" name="read" id="read" value="1">
            <label for="read">Tylko przeczytane</label>
            <button type="submit" name="search" class="btn btn-primary">Szukaj</button>
            <button type="submit" name="sort" value="asc" class="btn btn-primary">Sortuj A-Z</button>
            </div>
    </form>
    <hr width="70%">
    <form action="home">
        <button type="submit" class="btn btn-secondary" style="margin-top: 10px;">Powrót</button>
    </form>
    </div>
    <table>
        <tr>
            <th>Tytuł</th>
            <th>Autor</th>
            <th>Gatunek</th>
            <th>Ocena</th>
            <th>Akcje</th>
        </tr>
        <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["author"] . "</td>";
        echo "<td>" . $row["genre"] . "</td>";
        echo "<td style='text-align: center;'>" . $row["rating"] . " / 10</td>";
        echo "<td>";
        echo "<div class='action-btns'>";
        echo "<form action='view_notes' method='GET'>";
        echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
        echo "<button type='submit' class='btn btn-info'>Zobacz Notatki</button>";
        echo "</form>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Brak książek w bibliotece. &#128543;</td></tr>";
}
$conn->close();
?>

    </table>
    <div class="centered-buttons">
        <form action="home">
            <button type="submit" class="btn btn-secondary">Powrót</button>
        </form>
    </div>
</body>

</html>