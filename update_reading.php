<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $bookId = $_POST['id'];

    // Prepare an SQL statement using a placeholder for the book ID
    $updateQuery = "UPDATE books SET reading = 0 WHERE id = ?";
    
    // Create a prepared statement
    $stmt = $conn->prepare($updateQuery);

    if ($stmt) {
        // Bind the book ID parameter to the prepared statement
        $stmt->bind_param("i", $bookId);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Redirect back to the previous page or wherever you want
            header('Location: read');
            exit;
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
