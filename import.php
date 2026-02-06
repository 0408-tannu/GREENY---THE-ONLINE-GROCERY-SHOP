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
mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("❌ Connection failed: " . mysqli_connect_error() . "\n");
}

echo "\n🔄 Starting database import...\n\n";

$sqlFile = __DIR__ . '/database/online_grocery_shop.sql';
$sql = file_get_contents($sqlFile);

if (mysqli_multi_query($conn, $sql)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    
    echo "✅ Database imported successfully!\n\n";
} else {
    die("❌ Error: " . mysqli_error($conn) . "\n");
}

$tables = mysqli_query($conn, "SHOW TABLES FROM defaultdb");
$count = 0;
echo "📊 Tables created:\n";
while ($row = mysqli_fetch_array($tables)) {
    $count++;
    echo "   ✓ {$row[0]}\n";
}

echo "\n✅ Total: $count tables\n";
echo "👉 Visit http://localhost:8000 to view the app\n\n";

mysqli_close($conn);
?>
