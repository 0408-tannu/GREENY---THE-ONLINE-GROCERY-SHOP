<?php
// --- PHP LOGIC FIRST ---
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Enable MySQLi error reporting to catch silent database errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include_once '../../config/db_connect.php';

// Redirect if cart is empty or user is not logged in
if (empty($_SESSION['cart']) || !isset($_SESSION['user_id'])) {
    header('Location: /grocershopNew/index.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// --- Calculate totals from the cart ---
$shipping = 50;
$coupon_discount =0;

$sub_total = 0;
$total_items_in_cart = 0;
$products_in_cart = []; // Array to hold full product details

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    if (!empty($product_ids)) {
        $ids_string = implode(',', array_map('intval', $product_ids));
        // Fetch all details needed for the cart, including stock_quantity for final check
        $sql = "SELECT id, name, final_price, image_url, stock_quantity FROM products WHERE id IN ($ids_string)";
        $result = $conn->query($sql);
        if ($result) {
            while ($product = $result->fetch_assoc()) {
                $quantity = $_SESSION['cart'][$product['id']];
                $sub_total += $product['final_price'] * $quantity ;
                $total_items_in_cart += $quantity;
                $products_in_cart[] = $product; // Store full product data
            }
        }
    }
}
$grand_total = $sub_total + $shipping; // Simplified total for now

// --- Logic to place the order ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $address = htmlspecialchars($_POST['full-name'] . "\n" . $_POST['address']);
    // $payment_method = htmlspecialchars($_POST['payment']); // Not currently used in DB insert

    // --- 1. START TRANSACTION ---
    $conn->begin_transaction();

    try {
        // --- 2. Final Stock Safety Check ---
        foreach ($products_in_cart as $product) {
            // Re-check current stock against cart quantity one last time
            if ($_SESSION['cart'][$product['id']] > $product['stock_quantity']) {
                throw new Exception("Stock changed for " . $product['name'] . ". Not enough available.");
            }
        }

        // --- 3. Insert Order ---
        $order_sql = "INSERT INTO orders (user_id, total_amount, shipping_address, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($order_sql);
        $stmt->bind_param("ids", $user_id, $grand_total, $address);
        $stmt->execute();
        $order_id = $conn->insert_id;
        $stmt->close();

        // --- 4. Prepare Item and Stock Statements ---
        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price_at_time_of_purchase) VALUES (?, ?, ?, ?)";
        $item_stmt = $conn->prepare($item_sql);

        // The query to subtract quantity
        $update_stock_sql = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
        $update_stock_stmt = $conn->prepare($update_stock_sql);

        // --- 5. Loop through cart items ---
        foreach ($products_in_cart as $product) {
            $product_id = $product['id'];
            $quantity = $_SESSION['cart'][$product_id];
            $price = $product['final_price'];

            // A. Insert into order_items
            $item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            if (!$item_stmt->execute()) {
                 throw new Exception("Failed to add order item: " . $item_stmt->error);
            }

            // B. Update Stock
            $update_stock_stmt->bind_param("ii", $quantity, $product_id);
            
            if (!$update_stock_stmt->execute()) {
                // If execute returns false, throw an exception with the DB error message
                throw new Exception("Stock update failed for product ID $product_id. Error: " . $update_stock_stmt->error);
            }
            
            // Optional: Check if any rows were actually affected (e.g., if product ID somehow didn't exist)
            if ($update_stock_stmt->affected_rows === 0) {
                 // This might happen if the product ID is wrong, or if stock_quantity was already NULL/0 and the math failed silently depending on SQL mode.
                 // throw new Exception("Stock update likely failed. No rows changed for product ID $product_id.");
            }
        }

        $item_stmt->close();
        $update_stock_stmt->close();

        // --- 6. Commit Transaction ---
        $conn->commit();

        unset($_SESSION['cart']); // Clear the cart
        header('Location: /grocershopNew/pages/cart/order_success.php?order_id=' . $order_id);
        exit();

    } catch (Exception $e) {
        // --- 7. Rollback on Error ---
        $conn->rollback();
        // Redirect back to cart with the specific error message so we know what happened
        $_SESSION['cart_error'] = "Order Failed: " . $e->getMessage();
        header('Location: /grocershopNew/pages/cart/cart.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Greeny</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/pages_css/cart/check_out.css">
</head>
<body>

   

    <main class="container">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold">Checkout</h1>
            <p class="text-gray-500 mt-2">
                <a href="/grocershopNew/index.php">Home</a> / 
                <a href="/grocershopNew/pages/cart/cart.php">Shopping Cart</a> / 
                <span class="text-gray-400">Checkout</span>
            </p>
        </div>

        <form method="POST" action="checkout.php">
            <div class="grid-container">
                <div class="left-column">
                    <div class="card-box">
                        <h2 class="text-xl font-semibold mb-4">Delivery Address</h2>
                        <div class="form-grid">
                            <div class="grid-col-span-2">
                                <label for="full-name" class="form-label">Full Name</label>
                                <input type="text" id="full-name" name="full-name" class="form-input" placeholder="John Doe" required>
                            </div>
                            <div class="grid-col-span-2">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" id="address" name="address" class="form-input" placeholder="123 Plant Street" required>
                            </div>
                            <div>
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="city" class="form-input" placeholder="Surat" required>
                            </div>
                            <div>
                                <label for="state" class="form-label">State</label>
                                <input type="text" id="state" name="state" class="form-input" placeholder="Gujarat" required>
                            </div>
                            <div>
                                <label for="zip" class="form-label">ZIP Code</label>
                                <input type="text" id="zip" name="zip" class="form-input" placeholder="395001" required>
                            </div>
                            <div>
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="+91 12345 67890" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <h2 class="text-xl font-semibold mb-4">Select Payment Method</h2>
                        <div class="space-y-4">
                            <div class="payment-method selected">
                                <input type="radio" name="payment" id="gpay" value="Google Pay" checked>
                                <label for="gpay">
                                    <i class="fab fa-google-pay text-gray-700"></i>
                                    <span>Google Pay</span>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" name="payment" id="cod" value="Cash On Delivery">
                                <label for="cod">
                                    <i class="fas fa-money-bill-wave" style="color: #16a34a;"></i>
                                    <span>Cash On Delivery</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card-box sticky-summary">
                        <h2 class="text-xl font-semibold border-b mb-4">Order Summary</h2>
                        <div class="space-y-3 text-gray-500">
                            <div class="summary-item">
                                <span>Items:</span>
                                <span><?php echo $total_items_in_cart; ?></span>
                            </div>
                            <div class="summary-item">
                                <span>Sub Total:</span>
                                <span><?php echo number_format($sub_total, 2); ?>  Rs.</span>
                            </div>
                            <div class="summary-item">
                                <span>Shipping:</span>
                                <span><?php echo number_format($shipping, 2); ?></span>
                            </div>
                            <div class="summary-item">
                                <span>Coupon Discount:</span>
                                <span>-<?php echo number_format($coupon_discount, 2); ?>Rs.</span>
                            </div>
                            <div class="border-t mt-4 summary-item total-summary">
                                <span>Total</span>
                                <span class="text-green-600"><?php echo number_format($grand_total, 2); ?>  Rs.</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn-primary">Confirm Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
    
    <script>
        // JavaScript to handle payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', () => {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                method.classList.add('selected');
                method.querySelector('input[type="radio"]').checked = true;
            });
        });
    </script>

</body>
</html>