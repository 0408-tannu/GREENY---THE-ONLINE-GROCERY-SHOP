<?php
// --- PHP LOGIC FIRST ---
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Get the order ID from the URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Successful! - Greeny</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .success-container {
            text-align: center;
            padding: 4rem 1rem;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin: 3rem auto;
            max-width: 600px;
        }
        .success-icon {
            font-size: 4rem;
            color: #22c55e; /* Green */
        }
        .success-container h1 {
            font-size: 2.5rem;
            margin: 1rem 0;
        }
        .success-container p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 2rem;
        }
        .continue-shopping-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--color-green);
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="container">
        <div class="success-container">
            <div class="success-icon">&#10004;</div> <h1>Thank You For Your Order!</h1>
            <p>Your order has been placed successfully.</p>
            <p>Your Order Number is: <strong>#<?php echo $order_id; ?></strong></p>
            <a href="/pages/products/products.php" class="continue-shopping-btn">Continue Shopping</a>
        </div>
    </main>

</body>
</html>