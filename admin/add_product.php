<?php
// --- PHP LOGIC FIRST ---

// 1. Include security and database connection
include __DIR__ . '/../includes/admin_auth.php';
include __DIR__ . '/../config/db_connect.php';

$message = '';

// 2. Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. Get all form data (including new fields)
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $regular_price = $_POST['regular_price'] ?? 0;
    $final_price = $_POST['final_price'] ?? 0;
    $rating = $_POST['rating'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';
    $category_id = $_POST['category_id'] ?? 0;

    // Handle new promotional fields
    $is_featured = isset($_POST['is_featured']) ? 1 : 0; // Checkbox value will be 1 if checked, 0 otherwise
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    $is_premium = isset($_POST['is_premium']) ? 1 : 0;
    $discount_percentage = $_POST['discount_percentage'] ?? 0;
    $offer_tag = $_POST['offer_tag'] ?? '';

    // --- AUTOMATIC FINAL PRICE CALCULATION (PHP) ---
    $final_price = $regular_price; // Start with the regular price
    if ($discount_percentage > 0) {
        $discount_amount = ($regular_price * $discount_percentage) / 100;
        $final_price = $regular_price - $discount_amount;
    }
    // --- END CALCULATION ---


    $stock_quantity = (int)$_POST['stock_quantity'];

    // 4. Use prepared statements for security
    $sql = "INSERT INTO products (name, description, regular_price, final_price, rating, image_url, category_id, is_featured, is_popular, is_premium, discount_percentage, offer_tag , stock_quantity) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?)";

    $stmt = $conn->prepare($sql);
    // 'ssddisiiiiis' corresponds to the data types: string, string, double, double, integer, etc.
    $stmt->bind_param("ssdddsiiiiis", $name, $description, $regular_price, $final_price, $rating, $image_url, $category_id, $is_featured, $is_popular, $is_premium, $discount_percentage, $offer_tag , $stock_quantity);
    if ($stmt->execute()) {
        $message = "New product added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
// Note: The connection is closed at the end of the HTML body.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_css/add_product.css">

    <link rel="stylesheet" href="../css/admin_css/header_admin.css">
</head>

<body>

    <?php
    // Include your standard admin header
    include __DIR__ . '/../admin/includes/header_admin.php';
    ?>

    <main class="admin-main-content">
        <div class="container-add_product">
            <h2>Add New Product</h2>

            <?php if (!empty($message)): ?>
                <div class="message-box">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>

            <form action="add_product.php" method="POST" class="add-product-form">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url" placeholder="e.g., assets/images/apple.jpg" required>

                <label for="category_id">Category ID:</label>
                <input type="number" id="category_id" name="category_id" required>

                <label for="regular_price">Regular Price:</label>
                <input type="number" id="regular_price" name="regular_price" step="0.01" required>

                <!-- <label for="final_price">Final Price:</label>
                <input type="number" id="final_price" name="final_price" step="0.01" required> -->

                <label for="rating">Rating (1-5):</label>
                <input type="number" id="rating" name="rating" step="0.1" min="0" max="5">

                <hr>
                <h3>Promotional Tags</h3>

                <div class="checkbox-group">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1">
                    <label for="is_featured">Featured Product</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="is_popular" name="is_popular" value="1">
                    <label for="is_popular">Popular Product</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="is_premium" name="is_premium" value="1">
                    <label for="is_premium">Premium Product</label>
                </div>

                <label for="discount_percentage">Discount Percentage:</label>
                <input type="number" id="discount_percentage" name="discount_percentage"
                    placeholder="e.g., 20 for 20% OFF">

                <label for="offer_tag">Offer Tag:</label>
                <input type="text" id="offer_tag" name="offer_tag" placeholder="e.g., Deal of the Day or Buy 1 Get 1">

                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity:</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="0" required>
                </div>

                <button type="submit" class="submit-btn ">Add Product</button>
            </form>
        </div>
    </main>

    <?php
    // Close the database connection at the very end
    $conn->close();
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const regularPriceInput = document.getElementById('regular_price');
            const discountInput = document.getElementById('discount_percentage');
            const finalPriceDisplay = document.getElementById('final_price_display');

            function calculateFinalPrice() {
                const regularPrice = parseFloat(regularPriceInput.value) || 0;
                const discount = parseInt(discountInput.value) || 0;
                let finalPrice = regularPrice;

                if (discount > 0 && discount <= 100) {
                    const discountAmount = (regularPrice * discount) / 100;
                    finalPrice = regularPrice - discountAmount;
                }

                // Display the result rounded to 2 decimal places
                finalPriceDisplay.value = finalPrice.toFixed(2);
            }

            // Listen for any input in the price and discount fields
            regularPriceInput.addEventListener('input', calculateFinalPrice);
            discountInput.addEventListener('input', calculateFinalPrice);
        });
    </script>
</body>

</html>