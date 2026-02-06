<?php
// --- PHP LOGIC FIRST ---
// Security check and session start
include '../includes/admin_auth.php';
include '../config/db_connect.php';

// Check if an order ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: view_orders.php");
    exit();
}

$order_id = (int)$_GET['id'];
$order = null;
$order_items = [];

// 1. Fetch the main order details (joining with users)
$stmt = $conn->prepare("SELECT orders.*, users.name AS customer_name, users.email FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $order = $result->fetch_assoc();
} else {
    // If order not found, redirect back to the list
    header("Location: view_orders.php");
    exit();
}
$stmt->close();

// 2. Fetch the items for this order (joining with products)
$stmt_items = $conn->prepare("SELECT oi.quantity, oi.price_at_time_of_purchase, p.name AS product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
while ($row = $result_items->fetch_assoc()) {
    $order_items[] = $row;
}
$stmt_items->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - #<?php echo htmlspecialchars($order['id']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_css/order_details.css">
</head>
<body>

    <?php include 'includes/header_admin.php'; ?>

    <main class="admin-main-content">
        <div class="order-details-container">
            <a href="view_orders.php" class="back-link">&larr; Back to All Orders</a>
            <h1>Order Details: #<?php echo htmlspecialchars($order['id']); ?></h1>

            <div class="info-grid">
                <div class="info-card customer-info">
                    <h2>Customer Information</h2>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Shipping Address:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                </div>

                <div class="info-card order-summary">
                    <h2>Order Summary</h2>
                    <p><strong>Order Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($order['order_date'])); ?></p>
                    <p><strong>Order Status:</strong> <span class="status <?php echo strtolower(htmlspecialchars($order['status'])); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
                    <p><strong>Order Total:</strong> <strong><?php echo number_format($order['total_amount'], 2); ?> Rs.</strong></p>                </div>
            </div>

            <div class="items-section">
                <h2>Items in this Order</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price per Item</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($order_items)): ?>
                            <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price_at_time_of_purchase'], 2); ?> Rs.</td>
                                <td><?php echo number_format($item['quantity'] * $item['price_at_time_of_purchase'], 2); ?> Rs.</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">No items found for this order.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php $conn->close(); ?>
</body>
</html> 