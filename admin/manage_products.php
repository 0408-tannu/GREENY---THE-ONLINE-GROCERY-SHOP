<?php
// Start the session to access session variables
session_start();

// 1. Security: Check if the user is an admin
include '../includes/admin_auth.php';

// 2. Database Connection
include '../config/db_connect.php';

// 3. Fetch all products from the database
$sql = "SELECT id, name, regular_price, final_price, category_id FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_css/manage_products.css">

</head>
<body>
<?php
// Security check MUST come first
include '../includes/admin_auth.php';

// Include your new admin header
include '../admin/includes/header_admin.php';
?>
    <div class="admin-container">
        <h1>Product Management</h1>
        <a href="add_product.php" class="add-new-btn">Add New Product</a>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Final Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 4. Loop through results and display each product
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['final_price']) . "  Rs.</td>";
                        echo "<td class='action-links'>";
                        // 5. Action links for Edit and Delete
                        echo "<a href='edit_product.php?id=" . $row['id'] . "' class='edit'>Edit</a>";
                        // Added an onclick confirmation for delete
                        echo "<a href='delete_product.php?id=" . $row['id'] . "' class='delete' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>