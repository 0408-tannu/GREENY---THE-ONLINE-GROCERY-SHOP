<?php
// This file assumes the admin_auth.php check has already run on the page including it.
// We still need to start a session to access the admin's name.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<header class="admin-header">
    <div class="header-content">
        <div class="logo-area">
            <h3>Admin Panel</h3>
        </div>
        <nav class="admin-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_products.php">Products</a>
            <a href="view_orders.php">Orders</a>
            <a href="view_customers.php">Customers</a>
        </nav>
        <div class="user-area">
            <!-- <span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span> -->
            <a href="../index.php" class="view-site-btn">View Site</a>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

