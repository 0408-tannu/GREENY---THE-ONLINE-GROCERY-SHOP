<?php
Require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Aiven MySQL Connection Details
$servername = $_ENV['HOST'];
$port       = $_ENV['PORT'];
$username   = $_ENV['USER'];
$password   = $_ENV['PASSWORD'];
$dbname     = $_ENV['DBNAME'];
$conn = mysqli_init();
// For production: Download CA certificate from Aiven and use it
mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
mysqli_options($conn, MYSQLI_INIT_COMMAND, "SET SESSION sql_mode='NO_ZERO_DATE,NO_ZERO_IN_DATE'");

// Enable SSL connection
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Connect to database with SSL
if (!mysqli_real_connect(
    $conn,
    $servername,
    $username,
    $password,
    $dbname,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
)) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

// Set character set to utf8mb4
mysqli_set_charset($conn, "utf8mb4");
?>
