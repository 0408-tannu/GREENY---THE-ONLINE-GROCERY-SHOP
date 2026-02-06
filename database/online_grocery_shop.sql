-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2025 at 02:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Disable foreign key checks before dropping tables
SET FOREIGN_KEY_CHECKS=0;

-- Drop existing tables
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_addresses`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_grocery_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image_url`) VALUES
(1, 'Vegetables', 'assets/images/vegetable-shopbydept.png'),
(2, 'Milk & Dairy', 'assets/images/milk.jpg'),
(3, 'Spices', 'assets/images/spices.jpg'),
(4, 'Drink', 'assets/images/drink.jpg'),
(5, 'Fruits', 'assets/images/fruits.jpg'),
(6, 'Bakery', 'assets/images/baking.jpg'),
(7, 'Snacks', 'assets/images/snackes.jpg'),
(8, 'Grains and Pulses', 'assets/images/wheat.jpg'),
(9, 'Frozen Food', 'assets/images/frozen.jpg'),
(10, 'Dry Fruit', 'assets/images/dryFruit.jpg'),
(11, 'Fats and Oils', 'assets/images/oils.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `shipping_address`, `status`, `order_date`) VALUES
(1, 1, 230.00, 'henny\ndfzvdf\nsurat, gujrat 395006', 'Pending', '2025-08-21 16:45:54'),
(2, 1, 230.00, 'henny\ndfzvdf\nsurat, gujrat 395006', 'Pending', '2025-08-21 16:49:06'),
(3, 1, 240.00, 'henny\ndfzvdf', 'Pending', '2025-08-21 16:51:28'),
(4, 2, 2235.00, 'tisha\nvirani', 'Pending', '2025-08-22 16:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_time_of_purchase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_time_of_purchase`) VALUES
(1, 3, 1, 3, 50.00),
(2, 3, 2, 1, 90.00),
(3, 4, 55, 1, 760.00),
(4, 4, 58, 1, 1425.00);

-- --------------------------------------------------------
--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `regular_price` decimal(10,2) NOT NULL,
  `final_price` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `discount_percentage` int(3) NOT NULL DEFAULT 0,
  `offer_tag` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `regular_price`, `final_price`, `rating`, `image_url`, `category_id`, `is_featured`, `is_popular`, `is_premium`, `discount_percentage`, `offer_tag`) VALUES
(1, 'Tomato', 'Fresh tomatos direct at your home from the farm .', 60.00, 50.00, 4.5, '\\grocershopNew\\assets\\images\\vegtomatos.png', 1, 0, 0, 0, 20, ''),
(2, 'cucamber', 'get fresh and very good quality cucumber from greeny ', 100.00, 90.00, 4.0, '\\grocershopNew\\assets\\images\\vegcucamber.png', 1, 0, 1, 0, 10, NULL),
(23, 'Bell paper', 'A delicious way to boost your health! Our bell peppers are packed with essential vitamins like C and A, and powerful antioxidants. Their crisp texture and sweet taste make them a perfect low-calorie addition to salads, fajitas, or simply enjoyed raw with your favorite dip. Eat colorful, feel great!', 150.00, 135.00, 4.0, '\\grocershopNew\\assets\\images\\vegbellpapers.png', 1, 0, 0, 1, 10, ''),
(25, 'Broccoli', 'Power up your meals with our nutrient-rich broccoli! A true superfood, it\'s loaded with vitamins K and C, fiber, and essential minerals. Adding broccoli to your diet is a simple and delicious way to support your immune system and overall wellness. Perfect for a healthy, vibrant dish.', 120.00, 102.00, 4.4, '\\grocershopNew\\assets\\images\\vegbroccoli.png', 1, 0, 1, 0, 20, 'deal of the day'),
(26, 'Cabbage', 'A humble powerhouse of nutrition! Our cabbage is an excellent source of Vitamin C and K, and it\'s packed with fiber to support healthy digestion. Its mild, slightly sweet flavor makes it a delicious and low-calorie addition to any diet. A simple way to add essential nutrients to your plate.', 60.00, 57.00, 4.0, '\\grocershopNew\\assets\\images\\vegcabbage.png', 1, 0, 0, 0, 5, ''),
(27, 'Capsicam', 'Add a vibrant splash of color and a sweet, mild crunch to your favorite dishes! Our fresh capsicums are incredibly versatile, perfect for slicing into salads, stir-frying with noodles, or roasting to bring out their delicious, smoky flavor. A must-have for any creative cook.', 100.00, 88.00, 3.9, '\\grocershopNew\\assets\\images\\vegcapsicum.png', 1, 0, 0, 0, 12, 'Buy 1 Get 1'),
(28, 'Carrots', 'Boost your health with the natural goodness of carrots! Famously rich in beta-carotene and Vitamin A, they\'re essential for supporting good vision and a healthy immune system. Enjoy them as a raw, low-calorie snack or cook them into your favorite dishes for a nutritious punch.', 60.00, 58.20, 4.0, '\\grocershopNew\\assets\\images\\vegcarrots.png', 1, 0, 0, 0, 3, ''),
(29, 'Cauliflower', 'With its mild, slightly nutty flavor and wonderfully versatile texture, our fresh cauliflower is a true kitchen chameleon. Roast it until golden and caramelized, steam it for a tender side dish, or blend it into a creamy, low-carb soup. A delicious and wholesome choice for any meal.', 80.00, 68.00, 5.0, '\\grocershopNew\\assets\\images\\vegcauliflower.png', 1, 0, 0, 0, 15, 'deal of the day'),
(31, 'Corn', 'Sweet, crunchy, and delicious. Perfect for grilling, boiling, or adding to salads. A fresh taste of summer.', 200.00, 170.00, 4.0, '\\grocershopNew\\assets\\images\\vegcorn.png', 1, 1, 0, 0, 15, ''),
(32, 'Green chillies', 'Green chillies fresh from the farm', 50.00, 40.00, 4.8, '\\grocershopNew\\assets\\images\\vegchillies.png', 1, 0, 0, 0, 20, ''),
(33, 'Corriender', 'get fresh corriender from greeny', 50.00, 42.50, 5.0, '\\grocershopNew\\assets\\images\\vegcorriender.png', 1, 0, 1, 0, 15, ''),
(34, 'egg plant', 'fresh and healthy eggplant. straight out of farmer\'s far,', 40.00, 38.00, 0.0, '\\grocershopNew\\assets\\images\\vegeggplant.png', 1, 0, 0, 0, 5, ''),
(35, 'lemon', 'lemon fresh out of plant\r\n', 100.00, 90.00, 4.0, '\\grocershopNew\\assets\\images\\veglemon.png', 1, 0, 1, 0, 10, ''),
(36, 'Potatos', 'make your dish healthy and tasty with the add of potatos which goes with every dish you serv', 80.00, 76.00, 4.8, '\\grocershopNew\\assets\\images\\vegpotatos.png', 1, 0, 0, 0, 5, ''),
(37, 'Sweet potatos', 'very healthy and tasty sweet potatos', 250.00, 225.00, 4.9, '\\grocershopNew\\assets\\images\\vegsweetpotatos.png', 1, 0, 0, 0, 10, ''),
(38, 'Bread', 'Get fresh and delicious bread from your nearest bakery shop', 75.00, 75.00, 4.5, '\\grocershopNew\\assets\\images\\bakery-bread.png', 6, 1, 0, 0, 15, 'deal of the day'),
(39, 'Burger buns', 'now burger buns is also available in our store. so do buy and try it ', 300.00, 270.00, 4.4, '\\grocershopNew\\assets\\images\\bakery-burger-buns.png', 6, 0, 1, 0, 10, ''),
(40, ' cheese', 'make your dish delicious with the spread of cheese', 75.00, 67.50, 4.6, '\\grocershopNew\\assets\\images\\dairy-cheese.png', 2, 0, 0, 1, 10, ''),
(41, 'Chocolate syrup', 'Hershey\'s Chocolate syrup', 300.00, 285.00, 4.0, '\\grocershopNew\\assets\\images\\bakery-hershey\'s-chocolate.png', 6, 0, 0, 1, 5, ''),
(43, 'Ketchup', 'Tomato Katchup', 170.00, 161.50, 4.2, '\\grocershopNew\\assets\\images\\bakery-kechupheinz.png', 6, 0, 0, 0, 5, 'Buy 1 Get 1'),
(44, 'Mayonnaise', 'Mayonnaise', 220.00, 198.00, 4.0, '\\grocershopNew\\assets\\images\\bakery-maionieseheinz.png', 6, 1, 0, 0, 25, 'deal of the day'),
(45, 'Mustard', 'Mustard ', 350.00, 315.00, 3.9, '\\grocershopNew\\assets\\images\\bakery-mustardheinz.png', 6, 0, 0, 0, 10, ''),
(46, 'Butter Milk', 'fresh Butter Milk from the farm', 67.00, 64.99, 4.0, '\\grocershopNew\\assets\\images\\dairy-amool-buttermilk .png', 2, 0, 1, 0, 3, 'festive season special'),
(47, 'Milk', 'Milk', 100.00, 90.00, 4.0, '\\grocershopNew\\assets\\images\\dairy-amool-milk.png', 2, 1, 0, 0, 10, ''),
(48, 'Organic valley Milk', 'Organic valley Milk', 250.00, 225.00, 4.0, '\\grocershopNew\\assets\\images\\dairy-milk-organicvelly.png', 2, 0, 0, 1, 10, 'deal of the day'),
(49, 'Paneer', 'Paneer', 120.00, 114.00, 4.2, '\\grocershopNew\\assets\\images\\dairy-paneer.jpg', 2, 0, 0, 0, 5, ''),
(50, 'Fresh Cream', 'Fresh Cream', 140.00, 126.00, 4.6, '\\grocershopNew\\assets\\images\\dairy-freashcream.png', 2, 0, 0, 1, 10, ''),
(51, 'Dahi', 'Dahi', 80.00, 68.00, 4.6, '\\grocershopNew\\assets\\images\\dairy-dahi.png', 2, 1, 0, 0, 15, 'Buy 1 Get 1'),
(52, 'Lassi', 'Lassi', 220.00, 211.20, 4.3, '\\grocershopNew\\assets\\images\\dairy-lassi.png', 2, 0, 0, 0, 4, ''),
(53, 'Butter Milk', 'Butter Milk', 180.00, 144.00, 4.6, '\\grocershopNew\\assets\\images\\dairy-amool-butter.png', 2, 0, 0, 0, 20, ''),
(54, 'Butter', 'butter', 160.00, 144.00, 3.9, '\\grocershopNew\\assets\\images\\dairy-amool-butter.png', 2, 0, 1, 0, 10, ''),
(55, 'Almonds', 'Almonds', 800.00, 760.00, 4.0, '\\grocershopNew\\assets\\images\\dry-almonds.png', 10, 0, 0, 1, 5, ''),
(56, 'Brazilnuts', 'Brazilnuts', 1200.00, 1104.00, 4.9, '\\grocershopNew\\assets\\images\\dry-brazilnuts.png', 10, 1, 0, 0, 8, 'festive season special'),
(57, 'Cashews', 'Cashews', 1000.00, 950.00, 4.0, '\\grocershopNew\\assets\\images\\dry-cashews.png', 10, 0, 0, 1, 30, 'deal of the day'),
(58, 'Chia seeds', 'Chia seeds', 1500.00, 1425.00, 4.0, '\\grocershopNew\\assets\\images\\dry-chiaseeds.png', 10, 0, 0, 0, 5, ''),
(59, 'Mixednuts', 'Mixednuts', 2000.00, 1800.00, 4.5, '\\grocershopNew\\assets\\images\\dry-mixednuts.png', 10, 0, 1, 0, 10, ''),
(60, 'Pista', 'Pista', 1300.00, 1235.00, 4.0, '\\grocershopNew\\assets\\images\\dry-pista.png', 10, 0, 0, 0, 5, 'festive season special'),
(61, 'Pumkinseds', 'Pumkinseds', 600.00, 582.00, 4.0, '\\grocershopNew\\assets\\images\\dry-pumkinseds.png', 10, 0, 0, 0, 15, 'deal of the day'),
(62, 'Sesameseeds', 'Sesameseeds', 800.00, 760.00, 4.0, '\\grocershopNew\\assets\\images\\dry-sesameseeds.png', 10, 1, 0, 0, 5, ''),
(63, 'Garam masala', 'Garam masala', 150.00, 145.50, 4.3, '\\grocershopNew\\assets\\images\\spices-garamamasala.png', 3, 0, 1, 0, 20, 'deal of the day'),
(64, 'Maggie masala', 'Maggie masala', 200.00, 190.00, 4.8, '\\grocershopNew\\assets\\images\\spices-maggiemasala.jpg', 3, 1, 0, 0, 5, 'Buy 1 Get 1'),
(65, 'Pav Bhaji masala', 'Pav Bhaji masala', 200.00, 190.00, 4.0, '\\grocershopNew\\assets\\images\\spices-pavbhaji-masala.png', 3, 0, 0, 0, 5, 'festive season special'),
(66, 'Sweet paprika', 'Sweet paprika', 350.00, 322.00, 4.6, '\\grocershopNew\\assets\\images\\spices-sweetpaprika.png', 3, 1, 0, 0, 8, ''),
(67, 'TATA Salt', 'TATA Salt', 150.00, 135.00, 4.9, '\\grocershopNew\\assets\\images\\spices-tata-salt.png', 3, 0, 1, 0, 10, ''),
(68, 'Turmeric powder', 'Turmeric powder', 120.00, 114.00, 4.2, '\\grocershopNew\\assets\\images\\spices-turmeric.png', 3, 0, 0, 0, 5, ''),
(69, 'Cinnamon powder', 'Cinnamon powder', 300.00, 270.00, 4.5, '\\grocershopNew\\assets\\images\\spices-cinnamon.png', 3, 0, 0, 1, 10, ''),
(70, 'Grapes', 'Grapes', 80.00, 76.00, 4.0, '\\grocershopNew\\assets\\images\\fruit-grapes.png', 5, 0, 0, 0, 5, ''),
(71, 'Guava', 'Guava', 50.00, 47.50, 4.0, '\\grocershopNew\\assets\\images\\fruit-guava.png', 5, 1, 0, 0, 5, ''),
(72, 'Lychee', 'Lychee', 150.00, 135.00, 3.9, '\\grocershopNew\\assets\\images\\fruit-lychee.png', 5, 1, 0, 0, 10, ''),
(73, 'Orange', 'Orange', 100.00, 90.00, 4.0, '\\grocershopNew\\assets\\images\\fruit-orange.png', 5, 0, 0, 0, 25, 'deal of the day'),
(74, 'Pineapple', 'Pineapple', 100.00, 90.00, 5.0, '\\grocershopNew\\assets\\images\\fruit-pineapple.png', 5, 0, 0, 1, 10, ''),
(75, 'Strawberry', 'Strawberry', 250.00, 237.50, 4.3, '\\grocershopNew\\assets\\images\\fruit-strawberry.png', 5, 0, 1, 0, 5, ''),
(76, 'Watermelon', 'Watermelon', 120.00, 108.00, 4.0, '\\grocershopNew\\assets\\images\\fruit-watermelon.png', 5, 0, 0, 0, 10, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'henny viradiya', 'henny.viradiya20@gmail.com', '$2y$10$hrNKZQ/QZ5poXtHDKvfl..C.7HTYpOiP2Sq.H4yJNzN66RUxy2ljC', '2025-08-08 07:39:10', 'admin'),
(2, 'tisha virani', 'tisha@gmail.com', '$2y$10$4fQvphI3UaUELTuBpzlIbuv9O4.6DQk/u7pFocqGLbzTnOFM49AhK', '2025-08-08 07:41:19', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `address_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--

--
-- Indexes for table `orders`
--

--
-- Indexes for table `order_items`
--

--
-- Indexes for table `products`
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `UNIQUE` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
