<?php
// Start a session to store messages or user data later if needed.
session_start();

// Include the database connection file.
include 'config/db_connect.php';

$message = '';

// Check if the form has been submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it to prevent SQL injection.
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Before inserting, check if the email already exists.
    $check_email_sql = "SELECT id FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_email_sql);
    
    if ($check_result->num_rows > 0) {
        $message = "Error: An account with this email already exists.";
    } else {
        // Hash the password for security. NEVER store passwords in plain text.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // SQL query to insert a new user into the 'users' table.
        // This is the 'Create' operation.
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        // Execute the query and check for success.
        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful! You can now log in.";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the database connection.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Link to your external CSS file. -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

    <!-- Include the header from your includes folder -->
    <?php include 'includes/header.php'; ?>

    <div class="container-register">
        <h2>Sign Up</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message-box">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="add-product-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="submit-btn">Register</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">Already have an account? <a href="login.php">Log In here</a>.</p>
    </div>

    <?php
    // THIS IS THE CORRECT PLACE to close the connection.
    // It's at the very end of the file, after all HTML has been generated.
    $conn->close();
    ?>

</body>
</html>
