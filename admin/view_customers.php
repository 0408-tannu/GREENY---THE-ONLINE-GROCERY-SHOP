<?php
// Security check and session start
include __DIR__ . '/../includes/admin_auth.php';

// Include the database connection
include __DIR__ . '/../config/db_connect.php';

// SQL query to fetch all users who have the 'customer' role
$sql = "SELECT id, name, email FROM users WHERE role = 'customer' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers - Admin Panel</title>
    
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_css/view_customer.css">
</head>
<body>

    <?php 
    // This includes your standardized admin header
    include __DIR__ . '/includes/header_admin.php'; 
    ?>

    <main class="admin-main-content">
        <div class="page-container">
            <h1>Customer Management</h1>

            <div class="data-section">
                <h3>All Registered Customers</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if the query returned any customers
                        if ($result && $result->num_rows > 0) {
                            // Loop through the results and display each customer
                            while ($customer = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "    <td data-label='Customer ID'>" . $customer['id'] . "</td>";
                                echo "    <td data-label='Name'>" . htmlspecialchars($customer['name']) . "</td>";
                                echo "    <td data-label='Email'>" . htmlspecialchars($customer['email']) . "</td>";
                                echo "    <td data-label='Action'><a href='customer_history.php?id=" . $customer['id'] . "' class='action-btn' style='background-color: #3498db; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px;'>View History</a></td>";   
                                echo "</tr>";
                            }
                        } else {
                            // Display a message if no customers are found
                            echo "<tr><td colspan='4' style='text-align:center; padding: 20px;'>No customers found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php
    // Close the database connection
    $conn->close();
    // You can include a footer here if you have one
    // include 'includes/footer_admin.php';
    ?>
</body>
</html>