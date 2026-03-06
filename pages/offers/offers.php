
<?php
// THIS MUST BE THE VERY FIRST LINE IN THE FILE.
// No spaces, no blank lines, no text before it.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Now you can include your other files
include __DIR__ . '/../../config/db_connect.php';
// ... rest of your page logic ...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery King - Special Offers</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/includes_css/header.css">
    <link rel="stylesheet" href="../../css/pages_css/offers/offers.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap" rel="stylesheet">

   
</head>
<body>


    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="container-offer">
        <div class="page-header">
            <h1 class="page-title">Today's Hottest Offers</h1>
            <p class="page-subtitle">Don't miss out on our exclusive deals! Fresh groceries at prices you'll love.</p>
        </div>

        <div class="offer-cards-grid">
            <!-- Buy 1 Get 1 Card -->
            <div class="offer-card card-bogo">
                <div>
                    <h2 class="offer-title">Buy 1 Get 1 FREE</h2>
                    <p class="offer-description">Double the goodness on select items. Add one to your cart, get another one free!</p>
                </div>
                <a href="/pages/products/products.php?deal=BUY+1+GET+1" class="offer-button">View BOGO Deals <i class="fas fa-arrow-right ml-2"></i></a>
            </div>

            <!-- 10% Off Card -->
            <div class="offer-card card-10-off">
                <div>
                    <h2 class="offer-title"> Up to 50% OFF</h2>
                    <p class="offer-description">Enjoy a sweet discount on a wide range of popular groceries and essentials.</p>
                </div>
                <a href="/pages/products/products.php?collection=on_sale"class="offer-button">Shop Discounted Items <i class="fas fa-arrow-right ml-2"></i></a>
            </div>

            <!-- 50% Off Card -->
            <div class="offer-card card-festive-off">
                <div>
                    <h2 class="offer-title">Festive season sale</h2>
                    <p class="offer-description">Huge savings! Get half price on clearance products while stocks last.</p>
                </div>
                <a href="/pages/products/products.php?deal=festive+season+special"class="offer-button">Find up to 40% Off Bargains <i class="fas fa-arrow-right ml-2"></i></a>
            </div>

            <!-- Deal of the Day Card -->
            <div class="offer-card card-deal-day">
                <div>
                    <h2 class="offer-title">Deal of the Day</h2>
                    <p class="offer-description">A special, limited-time offer on a featured product. Check back daily!</p>
                </div>
                <a href="/pages/products/products.php?deal=Deal+of+the+Day" class="offer-button">Grab Today's Deal <i class="fas fa-arrow-right ml-2"></i></a>
            </div>
        </div>
    </main>

    <?php
    // 5. Close the database connection and include the footer
    $conn->close();
    include __DIR__ . '/../../includes/footer.php'; // You can create and include a footer later
    ?>

</body>
</html>
