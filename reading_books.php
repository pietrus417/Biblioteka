<?php
    include 'check_secure.php';
    include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lista przeczytanych książek</title>
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
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
            justify-content: center; /* Wyśrodkowanie poziome */
    align-items: center; /* Wyśrodkowanie pionowe */
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
<nav class="top-menu">
<?php include 'navbar.php'; ?>
</nav>
    <h1>Lista przeczytanych książek</h1>
    <?php

// Pobierz liczbę przeczytanych książek (gdzie reading = 1)
$readQuery = "SELECT COUNT(*) AS readBooks FROM books WHERE reading = 1";
$readResult = $conn->query($readQuery);
$readRow = $readResult->fetch_assoc();
$readBooks = isset($readRow['readBooks']) ? $readRow['readBooks'] : 0;

// Pobierz ogólną liczbę książek w bibliotece
$totalQuery = "SELECT COUNT(*) AS totalBooks FROM books";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalBooks = isset($totalRow['totalBooks']) ? $totalRow['totalBooks'] : 0;

$conn->close();
?>

<div style="text-align: center; padding: 10px; margin-top: 20px; ">
    <div style="display: inline-block; border: 1px solid #ddd; padding: 10px;">
        <h4 style="color: #333; margin: 0; cursor: pointer;">
            Przeczytałaś
            <span style="font-weight: bold; color: #000;">
                <span style="color: black;" onmouseover="this.style.color='red'" onmouseout="this.style.color='black'">
                    <?php echo $readBooks; ?>
                </span>
            </span> z
            <span style="font-weight: bold; color: #000;">
                <span style="color: black;" onmouseover="this.style.color='red'" onmouseout="this.style.color='black'">
                    <?php echo $totalBooks; ?>
                </span>
            </span> wszystkich pozycji.
        </h4>
    </div>
</div>
<br>
        <div class="centered-buttons">
    <form action="view">
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
            
            $query = "SELECT * FROM books WHERE reading = 1"; // Wybieramy tylko przeczytane książki
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["author"] . "</td>";
        echo "<td>" . $row["genre"] . "</td>";
        echo "<td class='action-btns'>
                <form action='update_reading.php' method='POST'>
    <input type='hidden' name='id' value='" . $row["id"] . "'>
    <button type='submit' name='delete' class='btn btn-danger'>Usuń z listy przeczytanych</button>
</form>
            </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<tr><td colspan='4'>Jeszcze nic tutaj nie ma &#128543;</td></tr>";
}

$conn->close();
?>
    </table>
    <div class="centered-buttons">
    <form action="view">
        <button type="submit" class="btn btn-secondary">Powrót</button>
    </form>
    </div>
</body>
</html>
