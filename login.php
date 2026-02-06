<?php
session_start(); // Start the session at the very beginning of the script
include 'config/db_connect.php';

$error_message = ''; // Initialize an empty error message variable

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // SQL query to fetch user from the database by email
    // The role column is selected to store in the session
    $sql = "SELECT id, name, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session and redirect
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role']; // Store the user's role in the session
            
            // Redirect to the appropriate page based on the role
            if ($_SESSION['role'] === 'admin') {
                header("Location: ./admin/add_product.php");
            } else {
                header("Location: index.php");
            }
            exit(); // Always exit after a header redirect
        } else {
            // Incorrect password
            $error_message = "Invalid email or password.";
        }
    } else {
        // User not found
        $error_message = "Invalid email or password.";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <!-- Link to your external CSS file -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container-login">
        <h2>Log In</h2>
        <?php if (!empty($error_message)): ?>
            <div class="message-box">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        <form action="login.php" method="POST" class="add-product-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="submit-btn">Log In</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">Don't have an account? <a href="register.php">Sign Up here</a>.</p>
    </div>

    <?php
    // THIS IS THE CORRECT PLACE to close the connection.
    // It's at the very end of the file, after all HTML has been generated.
    $conn->close();
    ?>
</body>
</html>
