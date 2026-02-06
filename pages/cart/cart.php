<?php
// --- PHP LOGIC FIRST ---

// This MUST be at the top to access the session data.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../../config/db_connect.php';

// --- Logic to handle item DELETION ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $product_id_to_delete = (int) $_GET['id'];
    if (isset($_SESSION['cart'][$product_id_to_delete])) {
        unset($_SESSION['cart'][$product_id_to_delete]);
    }
    $_SESSION['cart_success'] = "Item removed from your cart.";
    header('Location: cart.php');
    exit();
}

// --- NEW: Logic to SET QUANTITY TO MAX AVAILABLE ---
if (isset($_GET['action']) && $_GET['action'] == 'setmax' && isset($_GET['id'])) {
    $product_id_to_update = (int) $_GET['id'];

    // Fetch REAL stock quantity from database
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id_to_update);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if ($product) {
        if ($product['stock_quantity'] > 0) {
            // Set session cart quantity to the exact stock amount
            $_SESSION['cart'][$product_id_to_update] = $product['stock_quantity'];
            $_SESSION['cart_success'] = "Quantity updated to match available stock.";
        } else {
             // If stock hit 0 in the meantime, remove item
             unset($_SESSION['cart'][$product_id_to_update]);
             $_SESSION['cart_error'] = "Sorry, that item just went out of stock.";
        }
    }
    
    header('Location: cart.php');
    exit();
}

// --- Logic to handle item UPDATE (increase/decrease quantity via +/-) ---
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id']) && isset($_GET['qty'])) {
    $product_id_to_update = (int) $_GET['id'];
    $new_quantity = (int) $_GET['qty'];

    // Ensure quantity is at least 1
    if ($new_quantity > 0) {
        // Check stock before updating
        $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id_to_update);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        if ($product) {
            // Don't allow setting quantity higher than available stock via the plus button
            if ($new_quantity <= $product['stock_quantity']) {
                $_SESSION['cart'][$product_id_to_update] = $new_quantity;
            } else {
                 // Optional: Set a session message about stock limit
                 $_SESSION['cart_error'] = "Cannot add more. Only " . $product['stock_quantity'] . " in stock.";
            }
        }
    } elseif ($new_quantity == 0) {
        // If quantity becomes 0, remove the item
        unset($_SESSION['cart'][$product_id_to_update]);
        $_SESSION['cart_success'] = "Item removed from your cart.";
    }
    
    header('Location: cart.php');
    exit();
}


$cart_items = [];
$grand_total = 0;
$total_items_in_cart = 0;
$stock_issue = false; // Flag to track stock issues

// Check if the cart session exists and is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);

    if (!empty($product_ids)) {
        $ids_string = implode(',', array_map('intval', $product_ids));
        // Fetch stock_quantity as well
        $sql = "SELECT id, name, final_price, image_url, stock_quantity FROM products WHERE id IN ($ids_string)";
        $result = $conn->query($sql);

        if ($result) {
            while ($product = $result->fetch_assoc()) {
                $product_id = $product['id'];
                $quantity = $_SESSION['cart'][$product_id];
                $subtotal = $product['final_price'] * $quantity;
                $grand_total += $subtotal;
                $total_items_in_cart += $quantity;

                // Check for stock issue
                $item_stock_issue = false;
                $stock_message = '';
                // We check if current quantity > stock OR stock is 0. 
                if ($product['stock_quantity'] <= 0) {
                     $stock_issue = true;
                     $item_stock_issue = true;
                     $stock_message = 'Out of Stock!';
                } elseif ($quantity > $product['stock_quantity']) {
                    $stock_issue = true;
                    $item_stock_issue = true;
                    $stock_message = 'Only ' . $product['stock_quantity'] . ' left!';
                }

                $cart_items[] = [
                    'id' => $product_id,
                    'name' => $product['name'],
                    'price' => $product['final_price'],
                    'image' => $product['image_url'],
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'stock_issue' => $item_stock_issue,
                    'stock_message' => $stock_message,
                    'stock_quantity' => $product['stock_quantity'] // Pass actual stock for UI logic
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Greeny</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/pages_css/cart/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stock-error {
            color: #dc3545;
            font-size: 0.9em;
            display: block;
            margin-top: 5px;
        }
        .checkout-btn.disabled {
            background-color: #ccc;
            cursor: not-allowed;
            pointer-events: none;
        }
        /* Styles for the new quantity selector buttons */
        .cart-quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            width: fit-content;
        }
        .qty-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f8f9fa;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }
        .qty-btn:hover {
            background-color: #e2e6ea;
        }
        .qty-btn.disabled {
             color: #ccc;
             pointer-events: none;
        }
        .cart-counter {
            padding: 5px 15px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }
        /* Style for the new "Set to max" link */
        .set-max-link {
            color: #198754; /* Bootstrap success green */
            text-decoration: underline;
            font-weight: 600;
            cursor: pointer;
            display: block; /* Make it sit on its own line below error */
            margin-top: 2px;
        }
        .set-max-link:hover {
            color: #146c43;
        }
    </style>
</head>

<body>

    <?php include_once '../../includes/header.php'; ?>

    <div class="page-header">
        <div class="container">
            <h1>Shopping Cart</h1>
        </div>
    </div>

    <main class="container cart-main">
        
        <?php if (isset($_SESSION['cart_error'])): ?>
            <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; padding: 10px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px;">
                <?php
                echo $_SESSION['cart_error'];
                unset($_SESSION['cart_error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['cart_success'])): ?>
            <div class="alert alert-success" style="color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 10px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px;">
                <?php
                echo $_SESSION['cart_success'];
                unset($_SESSION['cart_success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($cart_items)): ?>
            <div class="cart-layout">
                <div class="cart-table-container">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td class="product-cell">
                                        <a href="cart.php?action=delete&id=<?php echo $item['id']; ?>" class="remove-item"
                                            onclick="return confirm('Remove this item?');">&times;</a>
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>"
                                            alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </td>
                                    <td><?php echo number_format($item['price'], 2); ?> Rs.</td>
                                    <td>
                                        <div class="cart-quantity-selector">
                                            <a href="cart.php?action=update&id=<?php echo $item['id']; ?>&qty=<?php echo $item['quantity'] - 1; ?>" class="qty-btn minus-btn">
                                                <i class="fa-solid fa-minus"></i>
                                            </a>
                                            <div class="cart-counter"><?php echo $item['quantity']; ?></div>
                                            <?php $plus_disabled = ($item['quantity'] >= $item['stock_quantity']) ? 'disabled' : ''; ?>
                                            <a href="cart.php?action=update&id=<?php echo $item['id']; ?>&qty=<?php echo $item['quantity'] + 1; ?>" class="qty-btn plus-btn <?php echo $plus_disabled; ?>">
                                                <i class="fa-solid fa-plus"></i>
                                            </a>
                                        </div>

                                        <?php if ($item['stock_issue']): ?>
                                            <span class="stock-error">
                                                <?php echo $item['stock_message']; ?>
                                            </span>

                                            <?php if ($item['quantity'] > $item['stock_quantity'] && $item['stock_quantity'] > 0): ?>
                                                <a href="cart.php?action=setmax&id=<?php echo $item['id']; ?>" class="set-max-link">
                                                    Set to max available (<?php echo $item['stock_quantity']; ?>)
                                                </a>
                                            <?php elseif ($item['stock_quantity'] <= 0): ?>
                                                <a href="cart.php?action=delete&id=<?php echo $item['id']; ?>" 
                                                   style="color: #dc3545; text-decoration: underline; margin-left: 5px; font-size: 0.9em;"
                                                   onclick="return confirm('Remove this item from your cart?');">
                                                   (Remove)
                                                </a>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="subtotal-cell"><?php echo number_format($item['subtotal'], 2); ?> Rs.</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <ul>
                        <li><span>Items</span> <span><?php echo $total_items_in_cart; ?></span></li>
                        <li><span>Sub Total</span> <span><?php echo number_format($grand_total, 2); ?> Rs.</span></li>
                        <li><span>Shipping</span> <span>0.00 Rs.</span></li>
                        <li><span>Taxes</span> <span>0.00 Rs.</span></li>
                        <li><span>Coupon Discount</span> <span>-0.00 Rs.</span></li>
                    </ul>
                    <div class="summary-total">
                        <span>Total</span>
                        <span><?php echo number_format($grand_total, 2); ?> Rs.</span>
                    </div>
                    <a href="checkout.php" class="checkout-btn <?php echo $stock_issue ? 'disabled' : ''; ?>">Proceed to Checkout</a>
                    <?php if ($stock_issue): ?>
                        <p class="stock-error" style="text-align: center; margin-top: 10px;">Please resolve stock issues above to proceed.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="empty-cart-message">Your cart is empty. <a href="/grocershopNew/pages/products/products.php">Start
                    shopping!</a></p>
        <?php endif; ?>
    </main>

    <?php $conn->close(); ?>
</body>

</html>