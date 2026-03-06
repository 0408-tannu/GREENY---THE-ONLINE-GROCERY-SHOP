<?php
// --- PHP LOGIC FIRST ---

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include database configuration
include __DIR__ . '/../../config/db_connect.php';

// Check if a product ID is provided in the URL, redirect if not
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit();
}

// Sanitize the product ID
$product_id = (int) $_GET['id'];

// SQL to fetch the specific product and its category name using a JOIN
// NOTE: p.* will now include the 'stock_quantity' column you added to the database.
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the product details
$product = $result->fetch_assoc();

// If no product was found with that ID, redirect to the main shop page
if (!$product) {
    header("Location: products.php");
    exit();
}

// --- HTML OUTPUT SECOND ---
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Greeny</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/includes_css/header.css">
    <link rel="stylesheet" href="../../css/pages_css/product/product_detail.css">

</head>

<body class="page-background">

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="container-product-details">
        <div class="product-container" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
            <div class="product-image-section">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>

            <div class="product-info-section">
                <div class="product-header">
                    <span class="category-tag"><?php echo htmlspecialchars($product['category_name']); ?></span>
                    <div class="rating">
                        <svg class="rating-star" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <span class="rating-text"><?php echo htmlspecialchars($product['rating']); ?></span>
                    </div>
                </div>

                <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h1>

                <?php if (!empty($product['description'])): ?>
                    <p class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>
                <?php endif; ?>

                <div class="pricing-section">
                    <div class="regular-price-container">
                        <span class="price-label">Regular Price:</span>
                        <span class="regular-price"><?php echo htmlspecialchars($product['regular_price']); ?>
                            Rs.</span>
                    </div>
                    <div class="coupon-banner">
                        <p class="coupon-text">Digital Coupon Applied!</p>
                    </div>
                    <div class="final-price-container">
                        <span class="price-label final">Final Price:</span>
                        <span class="final-price"><?php echo htmlspecialchars($product['final_price']); ?> Rs.</span>
                        <span class="price-unit"> / each</span>
                    </div>
                </div>

                <div class="add-to-cart-container">
                    <?php 
                    // Check if the stock_quantity column exists and is greater than 0
                    if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): 
                    ?>
                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn minus-btn-detail">-</button>
                            <input id="quantity" type="text" value="1" class="quantity-input" readonly>
                            <button type="button" class="quantity-btn plus-btn-detail">+</button>
                        </div>

                        <button class="add-to-cart-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1" />
                                <circle cx="19" cy="21" r="1" />
                                <path
                                    d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                            </svg>
                            <span>Add to Cart</span>
                        </button>
                    <?php else: ?>
                        <div class="out-of-stock-message" style="color: #dc3545; font-weight: 700; font-size: 1.2em; padding: 15px; border: 2px solid #dc3545; border-radius: 8px; text-align: center; width: 100%;">
                            Currently Out of Stock
                        </div>
                    <?php endif; ?>
                </div>
                </div>
        </div>
    </main>


    <script>
        // (Your existing JavaScript block. No changes needed here.)
        document.addEventListener('DOMContentLoaded', () => {
            // --- Selectors for product_detail.php (Specific to the single product view) ---
            // These now match the HTML changes above.

            // Quantity Selector Elements for the DETAIL page
            const minusBtnDetail = document.querySelector('.quantity-selector .minus-btn-detail');
            const plusBtnDetail = document.querySelector('.quantity-selector .plus-btn-detail');
            const quantityInput = document.getElementById('quantity');

            // Add to Cart Button
            const addToCartButton = document.querySelector('.add-to-cart-container .add-to-cart-btn');

            // Product ID Source from the main product container
            const productContainer = document.querySelector('.product-container'); // Now this HAS data-product-id
            const productId = productContainer ? productContainer.getAttribute('data-product-id') : null;

            // --- Logic for the Quantity Selector on Detail Page ---
            // This will only run if the buttons exist (i.e., product is in stock)
            if (minusBtnDetail && plusBtnDetail && quantityInput) {
                plusBtnDetail.addEventListener('click', () => {
                    let currentQuantity = parseInt(quantityInput.value, 10);
                    currentQuantity++;
                    quantityInput.value = currentQuantity;
                });

                minusBtnDetail.addEventListener('click', () => {
                    let currentQuantity = parseInt(quantityInput.value, 10);
                    if (currentQuantity > 1) { // Ensure quantity doesn't go below 1
                        currentQuantity--;
                        quantityInput.value = currentQuantity;
                    }
                });
            } 

            // --- Logic for the "Add to Cart" Button on Detail Page ---
            // This will only run if the button exists (i.e., product is in stock)
            if (addToCartButton && productId) { // Ensure button and product ID are available
                addToCartButton.addEventListener('click', () => {
                    const quantity = parseInt(quantityInput.value, 10); // Get current quantity

                    // Basic validation
                    if (!productId || isNaN(quantity) || quantity < 1) {
                        alert('Invalid product ID or quantity. Cannot add to cart.');
                        console.error('Add to Cart Failed: productId:', productId, 'quantity:', quantity);
                        return; // Stop execution if data is invalid
                    }

                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('quantity', quantity);

                    fetch('/api/add_to_cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const cartCountSpan = document.querySelector('#cart-count');
                            if (cartCountSpan) {
                                cartCountSpan.textContent = data.total_items;
                                cartCountSpan.style.display = 'flex';
                            }
                            alert(data.message);
                        } else {
                            alert('Error: ' + (data.message || 'An unknown error occurred.'));
                            console.error('Server-side error:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch operation error:', error);
                        alert('A network error or critical error occurred. Please check the console.');
                    });
                });
            }
        });
    </script>

    <?php $conn->close(); ?>
</body>

</html>