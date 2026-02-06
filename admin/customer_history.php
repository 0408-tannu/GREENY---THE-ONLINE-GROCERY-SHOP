<?php
// 1. Security and Setup
session_start(); // Ensure session starts if admin_auth doesn't do it

// Adjust these paths if necessary, based on where you save this file
include '../includes/admin_auth.php';
include '../config/db_connect.php';

// 2. Get and Validate Customer ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // If no ID provided or invalid, redirect back to the customer list
    header("Location: view_customers.php"); // Replace with your actual customer list filename if different
    exit();
}

$customer_id = intval($_GET['id']);
$customer_name = "Unknown Customer";

// 3. Fetch Customer Name (for display purposes)
// Use prepared statements to prevent SQL injection
$stmt_cust = $conn->prepare("SELECT name FROM users WHERE id = ? AND role = 'customer'");
$stmt_cust->bind_param("i", $customer_id);
$stmt_cust->execute();
$cust_result = $stmt_cust->get_result();

if ($cust_result->num_rows > 0) {
    $customer_data = $cust_result->fetch_assoc();
    $customer_name = $customer_data['name'];
} else {
    // Customer ID doesn't exist or isn't a customer role
    $stmt_cust->close();
    header("Location: view_customers.php");
    exit();
}
$stmt_cust->close();


// 4. Fetch Order History for this specific customer
// IMPORTANT: Adjust table/column names match your database schema
$sql_orders = "SELECT id, order_date, total_amount, status 
               FROM orders 
               WHERE user_id = ? 
               ORDER BY order_date DESC";

$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $customer_id);
$stmt_orders->execute();
$orders_result = $stmt_orders->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History: <?php echo htmlspecialchars($customer_name); ?></title>
    
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_css/view_customer.css">
    
    <style>
        /* Add a simple back button style */
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn:hover { background-color: #5a6268; }
        /* Simple status badge styles */
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.9em; color: white; }
        .status-pending { background-color: #f0ad4e; }
        .status-completed, .status-delivered { background-color: #28a745; }
        .status-cancelled { background-color: #dc3545; }
    </style>
</head>
<body>

    <?php include 'includes/header_admin.php'; ?>

    <main class="admin-main-content">
        <div class="page-container">
            <a href="view_customers.php" class="back-btn">&larr; Back to Customers List</a>

            <h1>Order History</h1>
            <h3>Viewing orders for: <span style="color: #3498db;"><?php echo htmlspecialchars($customer_name); ?></span> (ID: <?php echo $customer_id; ?>)</h3>

            <div class="data-section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($orders_result && $orders_result->num_rows > 0) {
                            while ($order = $orders_result->fetch_assoc()) {
                                // Format date nicely
                                $formatted_date = date("M d, Y, h:i A", strtotime($order['order_date']));
                                // Format currency
                                $formatted_amount = number_format($order['total_amount'], 2);
                                
                                // Determine status class for basic styling
                                $status_class = 'status-' . strtolower(str_replace(' ', '-', $order['status']));

                                echo "<tr>";
                                echo "    <td data-label='Order ID'>#" . $order['id'] . "</td>";
                                echo "    <td data-label='Order Date'>" . $formatted_date . "</td>";
                                echo "    <td data-label='Total Amount'>$" . $formatted_amount . "</td>";
                                echo "    <td data-label='Status'><span class='status-badge $status_class'>" . htmlspecialchars(ucfirst($order['status'])) . "</span></td>";
                                
                                // Optional: Link to specific order details page if you have one
                                // echo "<td data-label='Actions'><a href='view_order.php?order_id=" . $order['id'] . "'>View Details</a></td>";
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center; padding: 30px; color: #666;'>This customer has not placed any orders yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php
    $stmt_orders->close();
    $conn->close();
    ?>
</body>
</html>