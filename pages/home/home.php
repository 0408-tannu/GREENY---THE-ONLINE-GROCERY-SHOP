<?php
// --- PHP LOGIC FIRST ---

// 1. Start the session for login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Include the database connection. This is essential.
include 'config/db_connect.php'; // Adjusted path

// --- HTML OUTPUT SECOND ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greeny - Your Online Grocery Shop</title>
    
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/includes_css/header.css">
    <link rel="stylesheet" href="/css/pages_css/home/herobanner.css">
    <link rel="stylesheet" href="/css/pages_css/home/shop_by_dept.css">
    <link rel="stylesheet" href="/css/pages_css/home/deal_of_the_day.css">
    <link rel="stylesheet" href="/css/pages_css/home/hot_offers.css">
    <link rel="stylesheet" href="/css/pages_css/product/product.css">
    <link rel="stylesheet" href="/css/pages_css/home/featured_product.css">
    <link rel="stylesheet" href="/css/pages_css/home/premium_popular.css">

</head>
<body>

    <?php 
    // 3. Include the main website header
    // It will be displayed at the top of the page.
    include 'includes/header.php'; // Adjusted path
    ?>

    <main>
        <?php 
        // 4. Now, include your page sections
        // These files will be placed inside the <body> and <main> tags.
        // They will automatically have access to the $conn database variable from step 2.
        include 'herobanner.php';
        include 'shop_by_dept.php';
        include 'deal_of_the_day.php';
        include 'hot_offers.php';
        include 'featured_products.php';
        include 'premium_popular_section.php'; 
        
        ?>
    </main>

    <?php
    // 5. Close the database connection and include the footer
    $conn->close();
    include 'includes/footer.php'; // You can create and include a footer later
    ?>

<script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Swiper for all slider containers
            const swiper = new Swiper('.department-slider', {
                loop: true,
                slidesPerView: 2, spaceBetween: 20,
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                breakpoints: {
                    768: { slidesPerView: 3, spaceBetween: 30 },
                    1024: { slidesPerView: 7, spaceBetween: 30 }
                }
            });

            new Swiper('.simple-slider', {
            loop: true,
            slidesPerView: 1, // Show 1 card on mobile
            spaceBetween: 20,
            navigation: {
                nextEl: '.simple-slider .swiper-button-next',
                prevEl: '.simple-slider .swiper-button-prev',
            },
            breakpoints: {
                768: { slidesPerView: 2, spaceBetween: 30 },
                1024: { slidesPerView: 4, spaceBetween: 30 } // Your setting for 4 slides
            }
        });

           // Initialize this specific slider
        new Swiper('.featured-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            navigation: {
                nextEl: '.featured-products-container .swiper-button-next',
                prevEl: '.featured-products-container .swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 4 }
            }
        });
                    const allAddToCartButtons = document.querySelectorAll('.add-to-cart-btn');

            allAddToCartButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    // Find the closest parent product card
                    const card = event.target.closest('.product-card, .about-product');
                    if (!card) return;

                    const productId = card.getAttribute('data-product-id');
                    
                    // For this simple card, we always add a quantity of 1
                    const quantity = 1;

                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('quantity', quantity);

                    // Send the data to your API
                    fetch('/api/add_to_cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const cartCountSpan = document.querySelector('#cart-count');
                            if (cartCountSpan) {
                                cartCountSpan.textContent = data.total_items;
                                cartCountSpan.style.display = 'flex';
                            }
                            alert(data.message); 
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        alert('A critical error occurred. Please check the console.');
                    });
                });
            });
                    });

 
        


    </script>
</body>
</html>