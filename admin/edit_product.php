<?php
session_start();
include '../includes/admin_auth.php';
include '../config/db_connect.php';

$product = null;
$message = '';

// PART 1: Handle the form submission (POST request) to update the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get form data
    $id = (int) $_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $regular_price = (float) $_POST['regular_price'];
    $final_price = (float) $_POST['final_price'];
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $category_id = (int) $_POST['category_id'];
    $stock_quantity = (int) $_POST['stock_quantity'];

    // Prepare UPDATE statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE products SET name = ?, regular_price = ?, final_price = ?, image_url = ?, category_id = ?, stock_quantity = ? WHERE id = ?");
    $stmt->bind_param("sddsiii", $name, $regular_price, $final_price, $image_url, $category_id, $stock_quantity, $id);
    if ($stmt->execute()) {
        // Redirect on success
        header("Location: manage_products.php?status=updated");
        exit();
    } else {
        $message = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// PART 2: Fetch the product data to display in the form (GET request)
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    } else {
        $message = "Product not found.";
    }
    $stmt->close();
} else {
    // Redirect if no ID is provided
    header("Location: manage_products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_css/edit_product.css">

</head>

<body>
    <?php
    // Security check MUST come first
    include '../includes/admin_auth.php';

    // Include your new admin header
    include '../admin/includes/header_admin.php';
    ?>
    <div class="form-container">
        <h2>Edit Product</h2>
        <?php if ($message)
            echo "<p class='message'>$message</p>"; ?>

        <?php if ($product): ?>
            <form action="edit_product.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

                <label for="regular_price">Regular Price:</label>
                <input type="number" id="regular_price" name="regular_price" step="0.01"
                    value="<?php echo $product['regular_price']; ?>" required>

                <label for="final_price">Final Price:</label>
                <input type="number" id="final_price" name="final_price" step="0.01"
                    value="<?php echo $product['final_price']; ?>" required>

                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url"
                    value="<?php echo htmlspecialchars($product['image_url']); ?>" required>

                <label for="category_id">Category ID:</label>
                <input type="number" id="category_id" name="category_id" value="<?php echo $product['category_id']; ?>"
                    required>

                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity:</label>
                    <input type="number" id="stock_quantity" name="stock_quantity"
                        value="<?php echo $product['stock_quantity']; ?>" required>
                </div>

                <button type="submit">Update Product</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
$conn->close();
?>