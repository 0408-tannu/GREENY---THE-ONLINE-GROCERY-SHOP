<?php
// --- PHP LOGIC FIRST ---
// 1. Security check, session start, and DB connection
include __DIR__ . '/../includes/admin_auth.php';
include __DIR__ . '/../config/db_connect.php';

// --- HANDLE STATUS UPDATE FORM SUBMISSION ---
$success_msg = "";
$error_msg = "";

// Define possible order statuses
$statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    // Get order ID and new status from the form
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    // Validate the new status
    if (in_array($new_status, $statuses)) {
        // Use a prepared statement to prevent SQL injection
        $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if ($update_stmt) {
            $update_stmt->bind_param("si", $new_status, $order_id);
            if ($update_stmt->execute()) {
                $success_msg = "Order #$order_id status updated to '$new_status' successfully.";
            } else {
                $error_msg = "Error updating order status: " . $conn->error;
            }
            $update_stmt->close();
        } else {
            $error_msg = "Error preparing statement: " . $conn->error;
        }
    } else {
        $error_msg = "Invalid status selected.";
    }
}

// --- 2. FETCH ALL ORDERS (Your existing query) ---
$sql = "SELECT 
            orders.id, 
            orders.order_date, 
            orders.total_amount, 
            orders.status, 
            users.name AS customer_name 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.order_date DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_css/view_orders.css">
    <style>
        /* Styles for the status update form */
        .status-form {
            display: flex;
            gap: 5px;
            align-items: center;
            justify-content: flex-start;
        }

        .status-select {
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .update-btn {
            padding: 6px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.2s;
        }

        .update-btn:hover {
            background-color: #218838;
        }

        /* Alert styles for success/error messages */
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Status label styles */
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
            display: inline-block;
        }

        .status.pending {
            background-color: #ffeeba;
            color: #856404;
        }

        .status.processing {
            background-color: #b8daff;
            color: #004085;
        }

        .status.shipped {
            background-color: #c3e6cb;
            color: #155724;
        }

        .status.delivered {
            background-color: #d4edda;
            color: #155724;
        }

        .status.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Adjust table header to match content */
        .admin-table th {
            text-align: left;
        }

        .action-links a {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <?php
    // Include your new admin header
    include __DIR__ . '/../admin/includes/header_admin.php';
    ?>

    <div class="admin-container">
        <h1>Order Management</h1>

        <?php
        // Display success or error messages
        if (!empty($success_msg)) {
            echo '<div class="alert alert-success">' . $success_msg . '</div>';
        }
        if (!empty($error_msg)) {
            echo '<div class="alert alert-danger">' . $error_msg . '</div>';
        }
        ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Current Status</th>
                    <th>Update Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    // 3. Loop through results and display each order
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                        echo "<td>" . date("M j, Y, g:i a", strtotime($row['order_date'])) . "</td>";
                        echo "<td>" . number_format($row['total_amount'], 2) . " Rs.</td>";

                        // Display current status with styling class
                        $status_class = strtolower(htmlspecialchars($row['status']));
                        echo "<td><span class='status $status_class'>" . htmlspecialchars($row['status']) . "</span></td>";

                        // --- STATUS UPDATE FORM (New Column) ---
                        echo "<td>";
                        echo "    <form method='POST' action='' class='status-form'>";
                        // Hidden input for order ID
                        echo "        <input type='hidden' name='order_id' value='" . $row['id'] . "'>";
                        echo "        <select name='new_status' class='status-select'>";
                        // Create dropdown options
                        foreach ($statuses as $status) {
                            // Pre-select the current status
                            $selected = ($status == $row['status']) ? 'selected' : '';
                            echo "<option value='$status' $selected>$status</option>";
                        }
                        echo "        </select>";
                        echo "        <button type='submit' name='update_status' class='update-btn'>Update</button>";
                        echo "    </form>";
                        echo "</td>";
                        // ---------------------------------------
                
                        echo "<td class='action-links'>";
                        // 4. Link to details page
                        echo "<a href='order_details.php?id=" . $row['id'] . "' class='edit'>View Details</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found yet.</td></tr>"; // Updated colspan to 7
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<?php
$conn->close();
?>