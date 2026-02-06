<?php
session_start();
include '../includes/admin_auth.php';
include '../config/db_connect.php';

// Check if an ID is passed in the URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Prepare a DELETE statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement and redirect
    if ($stmt->execute()) {
        header("Location: manage_products.php?status=deleted");
    } else {
        header("Location: manage_products.php?status=error");
    }
    
    $stmt->close();
    $conn->close();
} else {
    // If no ID is provided, just redirect
    header("Location: manage_products.php");
}
exit();
?>