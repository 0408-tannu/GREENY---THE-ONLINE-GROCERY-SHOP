<?php
// This file assumes the $conn (database connection) variable is available from the parent page (e.g., home.php).

// 1. Fetch products from the database where the 'is_featured' flag is set to 1.
$featured_sql = "SELECT * FROM products WHERE is_featured = 1"; // Limit to 8 items for the slider
$featured_result = $conn->query($featured_sql);
?>

<div class="products-bg">
<div class="featured-products-container">
    <p class="subtitle">Fresh Food</p>
    <div class="section-header">
        <h2 class="title">Featured Products</h2>
        <div class="nav-buttons">
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

    <div class="swiper-container-featured featured-slider">
        <div class="swiper-wrapper">
            <?php
            if ($featured_result && $featured_result->num_rows > 0) {
                while ($product = $featured_result->fetch_assoc()) {
                    echo '<div class="swiper-slide">';
                    echo '    <div class="product-card" data-product-id="' . $product['id'] . '">';
                    
                    if ($product['final_price'] < $product['regular_price']) {
                        echo '<div class="sale-badge">Sale!</div>';
                    }

                    echo '        <div class="product-image-container">';
                    echo '            <a href="/grocershopNew/pages/products/product_detail.php?id=' . $product['id'] . '"><img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '"></a>';
                    echo '        </div>';
                    echo '        <h3><a href="/grocershopNew/pages/products/product_detail.php?id=' . $product['id'] . '" style="text-decoration:none; color:inherit;">' . htmlspecialchars($product['name']) . '</a></h3>';
                    
                    echo '        <p class="price">';
                    if ($product['final_price'] < $product['regular_price']) {
                        echo '            <span class="original-price">' . number_format($product['regular_price'], 2) . '  Rs.</span>';
                    }
                    echo '            ' . number_format($product['final_price'], 2);
                    echo '         Rs. </p>';
                    
                    echo '       <button class="add-to-cart-btn btn-add">Add to cart</button>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No featured products available at the moment.</p>';
            }
            ?>
        </div>
    </div>
</div>
</div>