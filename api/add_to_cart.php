<?php
// api/add_to_cart.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit();
}

// Check for POST data
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    $total_items = array_sum($_SESSION['cart']);
    echo json_encode(['success' => true, 'message' => 'Product added!', 'total_items' => $total_items]);
} else {
    // This is the message you are seeing
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>