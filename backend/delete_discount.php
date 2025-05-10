<?php
include('../includes/db.php');  // Ensure this includes your PDO setup

$id = $_GET['id'];

try {
    $sql = "DELETE FROM discounts WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    // Bind the id parameter to the query
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../Staff-Pages/discounts.php");  // Redirect after successful delete
    } else {
        echo "Error deleting record.";  // Show error if delete fails
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();  // Display PDO error message
}
