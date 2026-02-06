<?php
// --- PHP LOGIC FIRST ---
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../../config/db_connect.php';

// This function is now the master template for displaying products.
// It can create a slider OR a simple grid based on the $is_slider flag.
include '../../includes/product_functions.php'; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Greeny</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/includes_css/header.css">
    <link rel="stylesheet" href="../../css/pages_css/product/product.css">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap" rel="stylesheet">

</head>
<body>

    <?php include '../../includes/header.php'; ?>
    <?php include '../../pages/products/hero_banner.php'; ?>

    <main class="container">
        <?php
        // SCENARIO 1: A specific category was clicked ("View All")
        if (isset($_GET['category_id'])) {
            $category_id = (int)$_GET['category_id'];
            
            // Get category name
            $cat_sql = "SELECT name FROM categories WHERE id = ?";
            $cat_stmt = $conn->prepare($cat_sql);
            $cat_stmt->bind_param("i", $category_id);
            $cat_stmt->execute();
            $category = $cat_stmt->get_result()->fetch_assoc();
            $page_title = $category ? $category['name'] : 'Category';

            // Get ALL products for that category (no LIMIT)
            $prod_sql = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ?";
            $prod_stmt = $conn->prepare($prod_sql);
            $prod_stmt->bind_param("i", $category_id);
            $prod_stmt->execute();
            $products_result = $prod_stmt->get_result();
            
            // Display as a simple GRID (is_slider = false)
            display_product_section($page_title, $products_result, $conn, false);
        }
                // SCENARIO 2: A specific DEAL was clicked
                
            elseif (isset($_GET['deal'])) {
                $deal_tag = $_GET['deal'];
                $sql = "SELECT p.*, c.name as category_name 
                        FROM products p 
                        JOIN categories c ON p.category_id = c.id 
                        WHERE p.offer_tag = ?";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $deal_tag);
                $stmt->execute();
                $products_result = $stmt->get_result();
                
                // THE FIX: Call the detailed function and tell it to create a grid
                display_product_section($deal_tag, $products_result, $conn, false);
            }


        // SCENARIO 3: A COLLECTION was clicked (Premium or Popular)

            elseif (isset($_GET['collection'])) {
                $collection_tag = $_GET['collection'];
                
                // 1. Create a "whitelist" of allowed column names for security.
                $allowed_columns = ['is_featured', 'is_popular', 'is_premium'];
    

                    // =======================================================
            // == ADD THIS NEW 'if' CONDITION INSIDE THE BLOCK ==
            // =======================================================
            if ($collection_tag === 'on_sale') {
                $page_title = 'Products On Sale';
                $sql = "SELECT p.*, c.name as category_name 
                        FROM products p 
                        JOIN categories c ON p.category_id = c.id 
                        WHERE p.discount_percentage > 0";
                $products_result = $conn->query($sql);
                // display_simple_product_grid($page_title, $products_result);
                display_product_section($page_title, $products_result, $conn, false);

            }
            // 


                // 2. Check if the tag from the URL is on our safe list.
                elseif (in_array($collection_tag, $allowed_columns)) {
                    

                // --- THIS IS THE FIX ---
                        // 1. Remove the "is_" prefix from the tag
                        $clean_tag = str_replace('is_', '', $collection_tag);

                        // 2. Create the user-friendly title from the cleaned tag
                        $page_title = ucwords($clean_tag) . ' Products';
                        // --- END OF FIX --
                        
                    // 3. If it's safe, build the SQL query using the validated tag.
                    // Note the backticks (`) around the column name.
                    $sql = "SELECT p.*, c.name as category_name 
                            FROM products p 
                            JOIN categories c ON p.category_id = c.id 
                            WHERE p.`$collection_tag` = 1"; 
    
                    $products_result = $conn->query($sql);
    
    
                    // Use the detailed card in a grid
                    display_product_section($page_title, $products_result, $conn, false);
    
                } else {
                    // If the tag is not on the safe list, show an error.
                    echo "<h1>Invalid Collection</h1><p>The requested product collection does not exist.</p>";
                }
            }

        // SCENARIO 4: The default full shop page
        else {
    
            
            // Display Department Sliders
            $department_ids = [1, 3,6,5, 2,10,4,7,9,8,11]; // Vegetables, Fruits, Dairy
            foreach ($department_ids as $id) {
                $cat_sql = "SELECT name FROM categories WHERE id = $id";
                $cat_name = $conn->query($cat_sql)->fetch_assoc()['name'];
                
                $prod_sql = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = $id";
                $products_result = $conn->query($prod_sql);
                // Display as a SLIDER (is_slider = true)
                display_product_section($cat_name, $products_result, $conn, true, $id);
            }
        }

        ?>
    </main>

    <?php
    // 5. Close the database connection and include the footer
    $conn->close();
    include '../../includes/footer.php'; // You can create and include a footer later
    ?>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Swiper for all slider containers
            const swiper = new Swiper('.swiper-container', {
                loop: true,
                slidesPerView: 2, spaceBetween: 20,
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                breakpoints: {
                    768: { slidesPerView: 3, spaceBetween: 30 },
                    1024: { slidesPerView: 5, spaceBetween: 30 }
                }
            });

      
// --- Your quantity counter script ---
document.querySelectorAll('.button-group').forEach(group => {
        const minusBtn = group.querySelector('.minus-btn');
        const plusBtn = group.querySelector('.plus-btn');
        const counterDiv = group.querySelector('.counter');

        if (plusBtn && minusBtn && counterDiv) {
            plusBtn.addEventListener('click', () => { counterDiv.textContent++; });
            minusBtn.addEventListener('click', () => {
                let count = parseInt(counterDiv.textContent);
                if (count > 1) { counterDiv.textContent--; }
            });
        }
    });

    // --- FINAL "ADD TO CART" SCRIPT ---
    document.querySelectorAll('.add-to-cart button').forEach(button => {
        button.addEventListener('click', (event) => {
            const card = event.target.closest('.about-product');
            if (!card) return;

            const productId = card.getAttribute('data-product-id');
            const counterDiv = card.querySelector('.counter');
            const quantity = counterDiv ? parseInt(counterDiv.textContent) : 1;
            
            if (!productId) {
                console.error("Product ID not found on the card.");
                return;
            }

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            // The final, correct path to your API file
            fetch('/grocershopNew/api/add_to_cart.php', {
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