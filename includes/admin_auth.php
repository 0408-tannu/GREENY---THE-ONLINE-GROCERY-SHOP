<?php

// Check if a session is not already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in or is not an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page if the user is not an admin
    header("Location: ../login.php");
    exit();
}
?>