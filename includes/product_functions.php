<?php
// includes/product_functions.php

// --- TOOL #1: The Detailed Card Function (used in sliders and some grids) ---
function display_product_section($title, $products_result, $conn, $is_slider, $category_id = null) {
    echo '<div class="department-section">';
    echo '    <div class="section-header">';
    echo '        <h2>' . htmlspecialchars($title) . '</h2>';
    if ($is_slider && $category_id !== null) {
        echo '        <a href="/grocershopNew/pages/products/products.php?category_id=' . $category_id . '" class="view-all-link">View All &rarr;</a>';
    }
    echo '    </div>';

    if ($is_slider) {
        echo '    <div class="swiper-container">';
        echo '        <div class="swiper-wrapper">';
    } else {
        echo '    <div class="product-grid-container">';
    }

    if ($products_result && $products_result->num_rows > 0) {
        while ($product = $products_result->fetch_assoc()) {
            $slide_class = $is_slider ? 'swiper-slide' : '';
            echo '<div class="' . $slide_class . '">';

            // --- DETAILED PRODUCT CARD HTML ---
            echo '    <div class="about-product" data-product-id="' . $product['id'] . '">';
            echo '        <div class="image-prod">
                                <a href="product_detail.php?id=' . $product['id'] . '">
                                    <img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '">
                                </a>
                            </div>';
            echo '        <span class="sp1">' . htmlspecialchars($product['category_name'] ?? 'General') . '</span>';
            echo '        <h5>
                                <a href="product_detail.php?id=' . $product['id'] . '">' . htmlspecialchars($product['name']) . '
                                </a>
                            </h5>';
            echo'           <div class="rating">
                        <svg class="rating-star" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                      
                            <span class="s2">(' . htmlspecialchars($product['rating']) . ')</span>
                            </div>';
            echo '        <div class="regular-price-section">
                                    <h6>Regular price:</h6>
                                            <span>' . htmlspecialchars($product['regular_price']) . 'Rs.</span>
                            </div>';
            if (isset($product['discount_percentage']) && $product['discount_percentage'] > 0) {
                echo '        <div class="image coupon-banner">
                                    <img src="/grocershopNew/assets/images/coupan.jpg" alt="Coupon"><div class="text">
                                            <span class="text-span">Digital coupon: ' . htmlspecialchars($product['discount_percentage']) . '% OFF</span>
                                    </div>
                                </div>';
            }
            echo '        <div class="price">
                                <h5>Final price:</h5>
                                            <h3>' . htmlspecialchars($product['final_price']) . 'Rs.</h3>
                            </div>';

            echo '        <div class="button-group-alignment">';
            
            // ============================================================
            // INVENTORY CHECK: Only show buttons if stock > 0
            // ============================================================
            if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0) {
                // IN STOCK: Show normal buttons
                echo '                <div class="button-group">
                                        <div class="action-button minus-btn">
                                            <i class="fa-solid fa-minus"></i>
                                        </div>
                                        <div class="counter">1</div>
                                                <div class="action-button plus-btn">
                                                    <i class="fa-solid fa-plus"></i>
                                                </div>
                                        </div>
                                        <div class="add-to-cart">
                                                <button type="button">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="11.575" viewBox="0 0 14 11.575"><g transform="translate(-132.997 -1553.667)"><g transform="translate(133.997 1554.667)"><path d="M9.539,8.826a.244.244,0,0,1-.127-.039H4.7a.227.227,0,0,1-.254,0c-.046,0-.1,0-.16,0H4.038a2.346,2.346,0,0,1-.319-.018,1.075,1.075,0,0,1-.883-.851,1.1,1.1,0,0,1,.516-1.173.123.123,0,0,0,.074-.165C2.988,4.55,2.561,2.548,2.2.858,2.186.788,2.158.766,2.085.766H1.162c-.19,0-.441,0-.693.006H.45A.435.435,0,0,1,0,.5V.306A.359.359,0,0,1,.4,0L1.463,0h.919a.431.431,0,0,1,.473.391c.042.188.083.379.122.565l0,.01.014.066.045.213c.046.212.046.212.253.212h8.277a.628.628,0,0,1,.191.022A.33.33,0,0,1,12,1.788v.17a6.265,6.265,0,0,0-.19.636c-.022.085-.044.168-.066.251-.276.993-.546,1.972-.845,3.058L10.6,7a.375.375,0,0,1-.425.328H3.895a.4.4,0,0,0-.118.014.365.365,0,0,0-.26.384.361.361,0,0,0,.334.33l.065,0h6.293a.49.49,0,0,1,.1.007.364.364,0,0,1,.287.386.357.357,0,0,1-.341.334c-.134,0-.269,0-.4,0H9.666A.243.243,0,0,1,9.539,8.826ZM6.859,6.6l3.008,0a.122.122,0,0,0,.142-.109c.2-.723.394-1.436.612-2.227l.224-.812c.112-.407.223-.813.336-1.224H3.218c0,.006,0,.01,0,.014a.077.077,0,0,0,0,.017l.147.683.056.261c.232,1.083.472,2.2.706,3.3.018.086.06.093.128.093Z" transform="translate(0 0)"></path><path d="M369.843,385.073h.215a.93.93,0,0,1,.612.366.858.858,0,0,1,.1.874.833.833,0,0,1-.678.531.883.883,0,0,1-1.035-.828.9.9,0,0,1,.729-.927C369.807,385.085,369.828,385.09,369.843,385.073Z" transform="translate(-365.426 -376.279)"></path><path d="M581.767,385.073h.215a.93.93,0,0,1,.611.366.857.857,0,0,1,.1.874.833.833,0,0,1-.678.531.883.883,0,0,1-1.035-.828.9.9,0,0,1,.729-.927C581.731,385.085,581.752,385.09,581.767,385.073Z" transform="translate(-571.964 -376.279)"></path></g></g></svg><span>Add</span>
                                                        </button>
                                                </div>';
            } else {
                // OUT OF STOCK: Show message instead
                echo '<div style="color: #dc3545; font-weight: 600; padding: 10px 0; text-align:center; width:100%;">
                        Out of Stock
                      </div>';
            }
            // ============================================================

            echo '            </div>';
            echo '    </div>'; // End about-product
            echo '</div>'; // End swiper-slide or empty div
        }
    } else {
        echo "<p>No products to display.</p>";
    }

    echo '        </div>'; 
    if ($is_slider) {
        echo '        <div class="swiper-button-next"></div>';
        echo '        <div class="swiper-button-prev"></div>';
        echo '    </div>';
    }
    echo '</div>';
}

// --- TOOL #2: The Simple Card Function ---
function display_simple_product_section($title, $products_result, $is_slider) {
    echo '<div class="department-section">';
    echo '    <div class="section-header">';
    echo '        <h2>' . htmlspecialchars($title) . '</h2>';
    echo '    </div>';

    if ($is_slider) {
        echo '    <div class="swiper-container simple-slider">';
        echo '        <div class="swiper-wrapper">';
    } else {
        echo '    <div class="product-grid-container">';
    }

    if ($products_result && $products_result->num_rows > 0) {
        while ($product = $products_result->fetch_assoc()) {
            $slide_class = $is_slider ? 'swiper-slide' : '';
            echo '<div class="' . $slide_class . '">';

            // --- SIMPLE PRODUCT CARD HTML ---
            echo '    <div class="product-card">';
            echo '        <a href="/grocershopNew/pages/products/product_detail.php?id=' . $product['id'] . '">';
            echo '            <img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '">';
            echo '        </a>';
            echo '        <h3>
                                <a href="/grocershopNew/pages/products/product_detail.php?id=' . $product['id'] . '">' . htmlspecialchars($product['name']) . '</a>
                          </h3>';
            echo '        <p class="price">' . htmlspecialchars($product['final_price']) .'  Rs.</p>';
            
            // ============================================================
            // INVENTORY CHECK FOR SIMPLE CARD
            // ============================================================
            if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0) {
                 // You didn't have an add button here before, but if you did, it would go here.
            } else {
                 echo '<p style="color: #dc3545; font-weight: 600; font-size: 0.9em;">Out of Stock</p>';
            }
            // ============================================================

            echo '    </div>';
            
            echo '</div>'; // Closes .swiper-slide or the empty div
        }
    } else {
        echo "<p>No products to display.</p>";
    }

    echo '    </div>'; 

    if ($is_slider) {
        echo '        <div class="swiper-button-next"></div>';
        echo '        <div class="swiper-button-prev"></div>';
        echo '    </div>'; // Closes .swiper-container
    }

    echo '</div>'; // Closes .department-section
}
?>