<?php
// --- PHP LOGIC FIRST ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../config/db_connect.php';
include_once '../includes/product_functions.php';

// Get the search query from the URL
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$search_results = null;

// Only proceed if the search query is not empty
if (!empty($search_query)) {
    // --- THE SEARCH QUERY ---
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.name LIKE ?";
    
    $stmt = $conn->prepare($sql);
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $search_results = $stmt->get_result();

    // =======================================================
    // == THE NEW "SMART REDIRECT" LOGIC ==
    // =======================================================
    // Check if the search returned exactly ONE result
    if ($search_results && $search_results->num_rows === 1) {
        // Fetch that single product
        $product = $search_results->fetch_assoc();
        $product_id = $product['id'];

        // Immediately redirect the user to the product's detail page
        header("Location: /grocershopNew/pages/products/product_detail.php?id=" . $product_id);
        exit(); // Stop the script to ensure the redirect happens
    }
    // =======================================================
    // If there are 0 or MORE THAN 1 results, the script will continue below
    // and display the normal results grid.
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/pages_css/product/product_detail.css">
</head>
<body>

    <?php include_once '../includes/header.php'; ?>

    <main class="container">
        <div class="search-results-header">
            <?php if (!empty($search_query)): ?>
                <h1>Search Results for: "<?php echo htmlspecialchars($search_query); ?>"</h1>
                <p><?php echo $search_results ? $search_results->num_rows : 0; ?> products found.</p>
            <?php else: ?>
                <h1>Please enter a search term.</h1>
            <?php endif; ?>
        </div>

        <?php
        // This part of the code will now only run if there are 0 or more than 1 results
        if (!empty($search_query)) {
            display_product_section("", $search_results, $conn, false);
        }
        ?>
    </main>

    <?php $conn->close(); ?>

</body>
</html>