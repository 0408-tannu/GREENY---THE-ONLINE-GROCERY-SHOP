
<?php
// This must be at the very top of the file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ** NEW: FETCH CATEGORIES FOR THE DROPDOWN **
// This check ensures a database connection is available.
if (!isset($conn)) {
    // The path must go up one level from 'includes/' to the main folder.
    include_once __DIR__ . '/../config/db_connect.php';
}
$categories_sql = "SELECT id, name FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);
?>


<div class="section1">
    <div class="container">
        <div class="info-section">
            <div>
                <span>Need help? Call Us: </span>
                <span class="yellow">+90 93169 29055</span>
            </div>
            <span>order now and get it within <span class="yellow">30 minutes </span></span></span>
            <div class="flex-end">
                <h6>Follow us</h6>
                <div class="round" >
                    <a href="https://www.instagram.com/">
                    <img src="/grocershopNew/assets/logo/instagram.png" alt="Instagram" ></a>
                        </div>
                        <div class="round">
                          <a href="https://www.facebook.com/">
                              <img src="/grocershopNew/assets/logo/facebook.png" alt="Facebook"> </a>

                        </div>
                        <a href="/grocershopNew/register.php" style="color:inherit; text-decoration:none;">
                            <h6>Register Now</h6>
                        </a>
                        <div class="verticle-line"></div>
                        <a href="/grocershopNew/login.php" style="color:inherit; text-decoration:none;">
                            <h6>SIGN IN</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="header2">
        
            <div class="container">
                <div class="search-section">
                 
                    <div class="logo-section">
                        <a href="/grocershopNew/index.php"><img src="/grocershopNew/assets/logo/greeny.png" alt="Greeny Logo"></a>
                    </div>
                    <form action="/grocershopNew/pages/search.php" method="GET" class="search-location">

                            <div class="search-section-in">
                                <select name="category">
                                    <option value="all">All Products</option>
                                    <?php
                                    // Dynamically populate this from your database
                                    // This assumes a $conn variable is available if you place this logic in the header
                                    $categories_sql = "SELECT id, name FROM categories ORDER BY name ASC";
                                    $categories_result = $conn->query($categories_sql);
                                    while ($cat = $categories_result->fetch_assoc()) {
                                        echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</option>';
                                    }
                                    ?>
                                </select>
                                <div class="verticle-line"></div>
                                <div class="search">
                                    <input type="text" name="query" placeholder="Search products..." required>
                                    <button type="submit" class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="location">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <span><input placeholder="Your location"/></span>
                            </div>

                            </form>
                <div class="wishlist-flex">
                    <!-- <div class="wishlist">
                        <div><span>2</span></div>
                        <h6>Wishlist</h6>
                    </div> -->
                    <a href="/grocershopNew/pages/cart/cart.php" class="cart-link">
                    <div class="cart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M24 48C10.7 48 0 58.7 0 72C0 85.3 10.7 96 24 96L69.3 96C73.2 96 76.5 98.8 77.2 102.6L129.3 388.9C135.5 423.1 165.3 448 200.1 448L456 448C469.3 448 480 437.3 480 424C480 410.7 469.3 400 456 400L200.1 400C188.5 400 178.6 391.7 176.5 380.3L171.4 352L475 352C505.8 352 532.2 330.1 537.9 299.8L568.9 133.9C572.6 114.2 557.5 96 537.4 96L124.7 96L124.3 94C119.5 67.4 96.3 48 69.2 48L24 48zM208 576C234.5 576 256 554.5 256 528C256 501.5 234.5 480 208 480C181.5 480 160 501.5 160 528C160 554.5 181.5 576 208 576zM432 576C458.5 576 480 554.5 480 528C480 501.5 458.5 480 432 480C405.5 480 384 501.5 384 528C384 554.5 405.5 576 432 576z"/></svg>
                        <div class="cart2">
                            <h5>Shopping cart</h5>
                           
                        </div>
                    </div>
                    </a>
                    <div class="account">
                         <img src="/grocershopNew/assets/logo/profile.svg"/>
                       
                        <div class="account-dropdown">
                        <a href="/grocershopNew/pages/account/my_account.php"><h6>My Account</h6></a>
                           
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-elements">
        <div class="container">
            <div class="elements">
                <div class="element1">
                <a href="/grocershopNew/pages/products/products.php">
                    <div class="department">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="6" x2="20" y2="6"></line><line x1="4" y1="18" x2="20" y2="18"></line></svg>
                           <span>Shop By Catagory</span>
                    </div>
                    </a>
                        <div class="elements-a">
                            <a href="/grocershopNew/index.php">Home</a>
                            <a href="/grocershopNew/pages/products/products.php">Shop</a>
                            <a href="/grocershopNew/pages/offers/offers.php">Offers</a>
                           
                            <a href="/grocershopNew/pages/contactus/contact_us.php">Contact Us</a>
                
                        </div>

                        <!-- <?php
                        // This 'if' statement checks if the logged-in user's role is 'admin'
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):

                            // ---- SMART LINK LOGIC ----
                            // Check if the current page is already in the 'admin' folder
                            $isAdminPage = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
                            // If it is, the path is empty. If not, the path is 'admin/'
                            $path = $isAdminPage ? '' : 'admin/';
                        ?>
                            <a href="<?php echo $path; ?>add_product.php">Add Product</a>
                            <a href="<?php echo $path; ?>manage_products.php">Manage (Edit/Delete)</a>
                            <a href="<?php echo $path; ?>view_orders.php">View Orders</a>

                        <?php endif; ?> -->

                    </div>
                    <div class="element2">
                    <a href="/grocershopNew/pages/products/products.php?deal=Deal+of+the+Day">

                        <button>Deal of the day</button>
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
