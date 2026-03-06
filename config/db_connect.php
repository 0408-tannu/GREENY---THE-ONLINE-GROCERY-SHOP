<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database Connection Details (works with InfinityFree MySQL & Aiven)
$servername = $_ENV['HOST'];
$port       = (int)$_ENV['PORT'];
$username   = $_ENV['USER'];
$password   = $_ENV['PASSWORD'];
$dbname     = $_ENV['DBNAME'];

$conn = mysqli_init();

mysqli_options($conn, MYSQLI_INIT_COMMAND, "SET SESSION sql_mode='NO_ZERO_DATE,NO_ZERO_IN_DATE'");

// Connect to database (no SSL for InfinityFree; SSL auto-negotiated for Aiven)
if (!mysqli_real_connect(
    $conn,
    $servername,
    $username,
    $password,
    $dbname,
    $port
)) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

// Set character set to utf8mb4
mysqli_set_charset($conn, "utf8mb4");
?>
