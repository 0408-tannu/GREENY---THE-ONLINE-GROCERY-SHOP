<?php


include 'includes/product_functions.php'; 
// This file assumes $conn and the display_product_section function are available from home.php
?>

<div class="deal-of-the-day">
<img src="/assets/images/cutout2.png" alt="cutout">
    <!-- <div class="cutout-flex">
        <img src="/assets/images/cutout2.png" alt="cutout">
    </div> -->

    <div class="container text">
        <h2>Deal of the day</h2>
        <div class="main-flex">

            <div class="text-section">
                <h4>Up To 30% OFF</h4>
                <span>On the selected products</span>
                <div class="offer">
                    <p>So what are you waiting for,</p>
                    <p class="color-green">Grab the product NOW !!</p>
                </div>
                <!-- <a href="/pages/products/products.php?deal=Deal+of+the+Day" class="order-btn"> -->
                <a href="/pages/products/products.php?deal=Deal+of+the+Day" class="order-btn">
                    <button>ORDER NOW</button>
                </a>
            </div>

            <div class="product-slider-wrapper">
                <?php
                // 1. Fetch products tagged as "Deal of the Day"
                $deal_sql = "SELECT * FROM products WHERE offer_tag = 'Deal of the Day' LIMIT 8";
                $deal_result = $conn->query($deal_sql);

                // 2. Call the simple function and tell it to create a slider
                display_simple_product_section(" ", $deal_result, true);
                ?>
            </div>
            </div>

        </div>

    </div>
</div>