<?php
// Security check and session start
include '../includes/admin_auth.php';

// Database connection
include '../config/db_connect.php';

// --- PHP LOGIC TO FETCH ALL DASHBOARD DATA ---

// 1. Fetch Today's Revenue
// $revenue_sql = "SELECT SUM(total_amount) AS total_revenue FROM orders WHERE DATE(order_date) = CURDATE()";
$revenue_sql = "SELECT SUM(total_amount) AS total_revenue FROM orders";
$revenue_result = $conn->query($revenue_sql);
// Use '?? 0' to prevent errors if there are no sales today
$today_revenue = $revenue_result->fetch_assoc()['total_revenue'] ?? 0;

// 2. Fetch Today's Orders
$orders_sql = "SELECT COUNT(id) AS total_orders FROM orders WHERE DATE(order_date) = CURDATE()";
$orders_result = $conn->query($orders_sql);
$today_orders = $orders_result->fetch_assoc()['total_orders'] ?? 0;

// 3. Fetch Total Pending Orders
$pending_sql = "SELECT COUNT(id) AS pending_orders FROM orders WHERE status = 'Pending'";
$pending_result = $conn->query($pending_sql);
$pending_orders = $pending_result->fetch_assoc()['pending_orders'] ?? 0;

// 4. Fetch Total Number of Products
$products_sql = "SELECT COUNT(id) AS total_products FROM products";
$products_result = $conn->query($products_sql);
$total_products = $products_result->fetch_assoc()['total_products'] ?? 0;

// 5. Fetch the 5 Most Recent Orders
$recent_orders_sql = "SELECT orders.id, users.name AS customer_name, orders.total_amount, orders.status
                      FROM orders
                      JOIN users ON orders.user_id = users.id
                      ORDER BY orders.order_date DESC
                      LIMIT 5";
$recent_orders_result = $conn->query($recent_orders_sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="../css/admin_css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>

    <?php 
    // This includes your standardized admin header
    include 'includes/header_admin.php'; 
    ?>

    <main class="admin-main-content">
        <div class="dashboard-container">
            <h1>Dashboard</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Today's Revenue</h4>
                    <p><?php echo number_format($today_revenue, 2); ?>  Rs.</p>
                </div>
                <div class="stat-card">
                    <h4>Today's Orders</h4>
                    <p><?php echo $today_orders; ?></p>
                </div>
                <div class="stat-card">
                    <h4>Pending Orders</h4>
                    <p><?php echo $pending_orders; ?></p>
                </div>
                <div class="stat-card">
                    <h4>Total Products</h4>
                    <p><?php echo $total_products; ?></p>
                </div>
            </div>

            <div class="data-section">
                <h3>Recent Orders</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are any recent orders to display
                        if ($recent_orders_result && $recent_orders_result->num_rows > 0) {
                            // Loop through the results and display each order
                            while ($order = $recent_orders_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "    <td>#" . $order['id'] . "</td>";
                                echo "    <td>" . htmlspecialchars($order['customer_name']) . "</td>";
                                echo "    <td>" . number_format($order['total_amount'], 2) . "  Rs.</td>";
                                echo "    <td><span class='status " . strtolower(htmlspecialchars($order['status'])) . "'>" . htmlspecialchars($order['status']) . "</span></td>";
                                echo "    <td><a href='order_details.php?id=" . $order['id'] . "' class='action-btn'>View</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            // Show a message if there are no orders yet
                            echo "<tr><td colspan='5' style='text-align:center;'>No recent orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php
    // Close the database connection at the end of the script
    $conn->close();
    ?>
</body>
</html>