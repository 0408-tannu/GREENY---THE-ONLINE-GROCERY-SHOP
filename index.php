<!-- to run this code. type -> php -S localhost:8000  in terminal -->
<?php
// Start the session to make session variables available
session_start();
// Include your database connection if you are fetching products on this page
include 'config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GroceryShop - Welcome</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/includes_css/header.css"> </head>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Ewert&family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lexend+Deca:wght@100..900&family=Limelight&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@200..700&family=Pinyon+Script&family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<body>

    <?php
    // This single line adds your entire new header
    // include 'includes/header.php';

    include 'pages/home/home.php';
    // include 'pages/products/products.php';
    ?>

    <main>
        
    </main>
    
    
   
</body>
</html>