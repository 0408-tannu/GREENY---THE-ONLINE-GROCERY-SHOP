<?php
// --- PHP LOGIC FIRST ---
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once __DIR__ . '/../../config/db_connect.php';

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// --- FETCH DATA FROM YOUR DATABASE ---

// 1. Fetch User Data
$user_stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// 2. Fetch Order History
$orders_stmt = $conn->prepare("SELECT id, order_date, status, total_amount FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
$orders = [];
while ($row = $orders_result->fetch_assoc()) {
    $orders[] = $row;
}

// 3. Fetch Saved Addresses
// You will need to build the functionality to add/edit addresses later.
// For now, this will show an empty list.
$addresses = []; 

// --- HANDLE FORM SUBMISSIONS ---
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullName = htmlspecialchars(trim($_POST['full-name']));
    // You would add a 'phone' column to your 'users' table to save this
    // $phone = htmlspecialchars(trim($_POST['phone']));

    $update_stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $update_stmt->bind_param("si", $fullName, $user_id);
    if ($update_stmt->execute()) {
        $_SESSION['name'] = $fullName; // Update the session name as well
        $updateMessage = '<div class="success-message">Profile updated successfully!</div>';
        // Re-fetch user data to show updated info
        $user['name'] = $fullName;
    }
}

// Determine which section of the page to show
$activePage = isset($_GET['page']) ? $_GET['page'] : 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Greeny</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/pages_css/account/my_account.css">
</head>
<body>

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <main class="container-account">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">My Account</h1>
            <p class="text-gray-500">Manage your profile, orders, and addresses.</p>
        </div>

        <div class="account-layout">
            <aside>
                <nav class="sidebar-nav">
                    <ul>
                        <li><a href="?page=profile" class="<?php echo ($activePage === 'profile') ? 'active' : ''; ?>"><i class="fas fa-user-circle"></i> My Profile</a></li>
                        <li><a href="?page=orders" class="<?php echo ($activePage === 'orders') ? 'active' : ''; ?>"><i class="fas fa-box"></i> Order History</a></li>
                        <li><a href="?page=addresses" class="<?php echo ($activePage === 'addresses') ? 'active' : ''; ?>"><i class="fas fa-map-marker-alt"></i> Saved Addresses</a></li>
                        <li><a href="/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </nav>
            </aside>

            <section>
                <div id="profile" class="content-box" style="display: <?php echo ($activePage === 'profile') ? 'block' : 'none'; ?>;">
                    <h2 class="text-xl font-semibold mb-6">My Profile</h2>
                    <?php echo $updateMessage; ?>
                    <form method="POST" action="?page=profile">
                        <div class="form-grid-2">
                            <div>
                                <label for="full-name" class="form-label">Full Name</label>
                                <input type="text" id="full-name" name="full-name" class="form-input" value="<?php echo htmlspecialchars($user['name']); ?>">
                            </div>
                            <div>
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                            </div>
                        </div>
                        <div style="margin-top: 1.5rem;">
                            <button type="submit" name="update_profile" class="btn">Save Changes</button>
                        </div>
                    </form>
                </div>

                <div id="orders" class="content-box" style="display: <?php echo ($activePage === 'orders') ? 'block' : 'none'; ?>;">
                    <h2 class="text-xl font-semibold mb-6">Order History</h2>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo date("M d, Y", strtotime($order['order_date'])); ?></td>
                                <td><span class="status status-<?php echo htmlspecialchars(strtolower($order['status'])); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                <td><?php echo number_format($order['total_amount'], 2); ?>  Rs.</td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($orders)): ?>
                                <tr><td colspan="4" style="text-align: center; padding: 2rem;">You have no past orders.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div id="addresses" class="content-box" style="display: <?php echo ($activePage === 'addresses') ? 'block' : 'none'; ?>;">
                    <h2 class="text-xl font-semibold mb-6">Saved Addresses</h2>
                    <p>You have no saved addresses.</p>
                </div>
            </section>
        </div>
    </main>

</body>
</html>