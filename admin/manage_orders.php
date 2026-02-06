<?php
include '../includes/admin_auth.php';
include '../config/db_connect.php';

// --- HANDLE STATUS UPDATE FORM SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    // Use a prepared statement to prevent SQL injection
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);

    if ($update_stmt->execute()) {
        $success_msg = "Order #$order_id status updated to '$new_status' successfully.";
    } else {
        $error_msg = "Error updating order status: " . $conn->error;
    }
    $update_stmt->close();
}

// --- FETCH ALL ORDERS (Newest first) ---
$sql = "SELECT orders.id, users.name AS customer_name, orders.total_amount, orders.status, orders.order_date
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.order_date DESC";
$result = $conn->query($sql);

// Define possible order statuses
$statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin Panel</title>
    <link rel="stylesheet" href="../css/admin_css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Basic styling for the update form within the table */
        .status-form {
            display: flex;
            gap: 5px;
        }
        .status-select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .update-btn {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .update-btn:hover {
            background-color: #218838;
        }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <?php include 'includes/header_admin.php'; ?>

    <main class="admin-main-content">
        <div class="dashboard-container">
            <h1>Manage Orders</h1>

            <?php
            // Display success or error messages
            if (isset($success_msg)) {
                echo '<div class="alert alert-success">' . $success_msg . '</div>';
            }
            if (isset($error_msg)) {
                echo '<div class="alert alert-danger">' . $error_msg . '</div>';
            }
            ?>

            <div class="data-section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Current Status</th>
                            <th>Update Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($order = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "    <td>#" . $order['id'] . "</td>";
                                echo "    <td>" . htmlspecialchars($order['customer_name']) . "</td>";
                                echo "    <td>" . date('M d, Y', strtotime($order['order_date'])) . "</td>";
                                echo "    <td>" . number_format($order['total_amount'], 2) . " Rs.</td>";
                                // Display current status with a class for styling
                                echo "    <td><span class='status " . strtolower(htmlspecialchars($order['status'])) . "'>" . htmlspecialchars($order['status']) . "</span></td>";
                                
                                // --- STATUS UPDATE FORM ---
                                echo "    <td>";
                                echo "        <form method='POST' action='' class='status-form'>";
                                echo "            <input type='hidden' name='order_id' value='" . $order['id'] . "'>";
                                echo "            <select name='new_status' class='status-select'>";
                                // Loop through statuses to create dropdown options
                                foreach ($statuses as $status) {
                                    // Pre-select the current status
                                    $selected = ($status == $order['status']) ? 'selected' : '';
                                    echo "<option value='$status' $selected>$status</option>";
                                }
                                echo "            </select>";
                                echo "            <button type='submit' name='update_status' class='update-btn'>Update</button>";
                                echo "        </form>";
                                echo "    </td>";

                                // Link to view full order details
                                echo "    <td><a href='order_details.php?id=" . $order['id'] . "' class='action-btn'>View Details</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center;'>No orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php $conn->close(); ?>
</body>
</html>