<div class="shopby-department">
    <div class="container">
        <h2>Shop By Department</h2>

        <div class=" container swiper-container department-slider">
            <div class="swiper-wrapper">
                <?php
                // 1. Fetch all categories from the database
                $sql = "SELECT id, name, image_url FROM categories ORDER BY name ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    // 2. Loop through each category
                    while ($category = $result->fetch_assoc()) {
                        
                        // 3. Each department is now a 'swiper-slide'
                        echo '<div class="swiper-slide">';
                        
                        // 4. This is YOUR UNCHANGED CARD CODE, wrapped in a link
                        echo '  <a href="/grocershopNew/pages/products/products.php?category_id=' . $category['id'] . '" class="department-link">';
                        echo '      <div class="image-box">';
                        echo '          <img src="/grocershopNew/' . htmlspecialchars($category['image_url']) . '" alt="' . htmlspecialchars($category['name']) . '">';
                        echo '          <span>' . htmlspecialchars($category['name']) . '</span>';
                        echo '      </div>';
                        echo '  </a>';

                        echo '</div>'; // End swiper-slide
                    }
                }
                ?>
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>


