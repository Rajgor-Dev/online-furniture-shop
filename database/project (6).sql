-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 10, 2025 at 08:43 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `password`) VALUES
('homedecor01@gmail.com', '$2y$10$/Ku0dQYJ0fuvGpMCNeRFKes06lPa.92bVOaLk6EWRmKzAnowqf6ny');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `add_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`customer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `img`) VALUES
(1, 'Chair', 'img/uploads/category_img/chair.jpg'),
(2, 'Sofa', 'img/uploads/category_img/sofa.jpg'),
(3, 'Bed', 'img/uploads/category_img/bed.jpg'),
(4, 'Table', 'img/uploads/category_img/table.jpg'),
(5, 'Dinning Table', 'img/uploads/category_img/DinningTable.jpg'),
(6, 'Bookcase', 'img/uploads/category_img/bookcase.jpg'),
(7, 'Cabinet', 'img/uploads/category_img/cabinet.jpg'),
(8, 'Wardrobe', 'img/uploads/category_img/wardrobe.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `city_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`city_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_name`, `state_name`) VALUES
('Ahmedabad', 'Gujarat'),
('Gandhinagar', 'Gujarat'),
('Kheda', 'Gujarat'),
('Nadiad', 'Gujarat'),
('Anand', 'Gujarat'),
('Vadodara', 'Gujarat'),
('Mehsana', 'Gujarat'),
('Sanand', 'Gujarat'),
('Kalol', 'Gujarat'),
('Vijapur', 'Gujarat'),
('Matar', 'Gujarat'),
('Borsad', 'Gujarat'),
('Patan', 'Gujarat'),
('Dahod', 'Gujarat'),
('Chhota Udaipur', 'Gujarat');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_no` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','deactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `password`, `contact_no`, `status`) VALUES
(1, 'Dev Rajgor', 'dev007@gmail.com', '$2y$10$vHf045BPysdKpo0rfEjnz.tFbZCbDkQG8t7GwG7kHXKyYufdT0bZG', '9998887745', 'active'),
(2, 'Harit Limbachiya', 'harit123@gmail.com', '$2y$10$yN4FN5Qfdl3oNwct338/KuRiQuZw4HpVxktkZdh2hf6a/IoBKJA/2', '9988775544', 'active'),
(3, 'Hutvik Modi', 'hutvik25@gmail.com', '$2y$10$KaQFoOpm0z76Gqg/EW1fvetxMb68f2JP8Mu3n6a8rq9lOqd0FAMVe', '9998887744', 'active'),
(4, 'Rushik', 'sharma@gmail.com', '$2y$10$MLM2VRGnjA.1Imkyb.mLB.gEzCnD7faMFlwidxJANffy4Op7QaUUu', '9988774455', 'active'),
(5, 'Harsh Patel', 'harsh45@gmail.com', '$2y$10$c2McXNZ7aLU4/UXc8bMuxerIpznZxcPc7xiXy5q/LWNJVSNPrxufK', '9988774499', 'deactive'),
(6, 'Jainam', 'jainam89@gmail.com', '$2y$10$DXVndJgLcWnKBj5qX4Na1OsNNNb.RVNZ6kEi/nxaKzkrt/t0bvMR6', '8200989878', 'active'),
(7, 'Vijay Soni', 'vijay23@gmail.com', '$2y$10$YbiyMolWKGSBzBh3VNfs9uqLJptVbZM4RPXJip3KUJybVyAUZEjWK', '8200989877', 'active'),
(8, 'avi786', 'avinayi786@gmail.com', '$2y$10$aPbEjFCTCbman3q7EjXMfOCO1QV5zA4ycrQYeX4eoUZ5hXjKBfiNm', '12589637787', 'active'),
(9, 'Axat', 'raval56@gmail.com', '$2y$10$QA0W4TUVDLuj69UfFzCvD.Lg60nQSHxDfFnVJUI49AKoGMlwFKdFS', '8200989878', 'active'),
(10, 'Bhargav', 'joshi12@gmail.com', '$2y$10$Hauwgx7jqFjAilmpjnMKRuirWSVHMMSP72IaMNCQVSc9w5cbelDJq', '8200989878', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `add_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `customer_id`, `rating`, `comment`, `add_on`) VALUES
(1, 2, 5, 'Zig Zag Chair is surprisingly comfortable and strong. Value for money. ', '2025-04-24 05:31:28'),
(2, 3, 5, 'Coffee Table come with excellent condition, On time delivery, Packing very nice.', '2025-04-24 05:37:08'),
(3, 6, 2, 'Waiting for Summer Sale.', '2025-04-24 05:44:12'),
(4, 1, 1, 'Provide AR model as soon as possible for exploring product easily.', '2025-04-24 06:00:11'),
(5, 4, 5, 'Loving Swan Bookcase is fantastic! It\'s stylish and perfect for organizing books.  ', '2025-04-24 06:08:38'),
(7, 7, 5, 'Waiting for Sale.', '2025-04-25 06:29:05');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` double(10,2) NOT NULL,
  `status` enum('Pending','Shipped','Delivered','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=FIXED;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `customer_id`, `address`, `city`, `state`, `order_date`, `total_amount`, `status`) VALUES
(1, 1, 'Department of Computer Science , HNGU.', 'Patan', 'Gujarat', '2025-03-28 14:07:55', 55000.00, 'Delivered'),
(2, 1, 'Yash Dham', 'Patan', 'Gujarat', '2025-03-28 14:11:54', 30000.00, 'Cancelled'),
(3, 1, '4,Vraj Nagar', 'Kalol', 'Gujarat', '2025-04-11 16:12:11', 70000.00, 'Delivered'),
(4, 3, '7,Himja society', 'Patan', 'Gujarat', '2025-04-18 04:54:28', 1000.00, 'Delivered'),
(5, 3, '6,Raliyatnagar Society.', 'Patan', 'Gujarat', '2025-04-18 05:37:08', 72500.00, 'Delivered'),
(6, 2, '8,Suvidhinath Society', 'Patan', 'Gujarat', '2025-04-18 07:54:48', 220000.00, 'Delivered'),
(7, 2, '5,Suvidhinath Society', 'Patan', 'Gujarat', '2025-04-18 08:00:19', 9500.00, 'Cancelled'),
(8, 4, '101,Yash Villa', 'Vadodara', 'Gujarat', '2025-04-24 05:16:42', 9500.00, 'Delivered'),
(9, 1, '7,Yash Dham', 'Patan', 'Gujarat', '2025-04-24 05:19:47', 10000.00, 'Delivered'),
(10, 2, '58,Malhar Society', 'Gandhinagar', 'Gujarat', '2025-04-24 05:22:19', 20000.00, 'Shipped'),
(11, 3, '5,Himja Society', 'Patan', 'Gujarat', '2025-04-24 05:33:57', 32000.00, 'Cancelled'),
(12, 6, '50,Dwarika Green Society', 'Ahmedabad', 'Gujarat', '2025-04-24 05:41:49', 6000.00, 'Delivered'),
(13, 4, '3,Ambikanagar Society', 'Mehsana', 'Gujarat', '2025-04-24 05:46:13', 20000.00, 'Pending'),
(14, 3, '5,Himja Society', 'Patan', 'Gujarat', '2025-04-24 05:52:56', 20000.00, 'Pending'),
(15, 1, '4,Tirupati Township', 'Patan', 'Gujarat', '2025-04-24 05:56:49', 82000.00, 'Pending'),
(16, 7, 'Department of Computer & Information Technology, HNGU.', 'Patan', 'Gujarat', '2025-04-24 08:06:30', 40000.00, 'Cancelled'),
(17, 8, '10,A2D Farm House', 'Patan', 'Gujarat', '2025-04-25 09:36:42', 44750.00, 'Delivered'),
(18, 9, '4, Yash Dham', 'Patan', 'Gujarat', '2025-04-26 14:44:26', 4127500.00, 'Pending'),
(19, 4, '3,Ambikanagar Society', 'Mehsana', 'Gujarat', '2025-04-29 09:58:37', 1000.00, 'Pending'),
(20, 7, 'Department of Computer & Information Technology, HNGU.', 'Patan', 'Gujarat', '2025-05-10 07:14:52', 640000.00, 'Pending'),
(21, 7, 'Department of Computer & Information Technology, HNGU.', 'Patan', 'Gujarat', '2025-05-10 08:23:07', 3200000.00, 'Pending'),
(22, 7, 'Department of Computer & Information Technology, HNGU.', 'Patan', 'Gujarat', '2025-05-10 08:27:24', 80000.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

DROP TABLE IF EXISTS `order_detail`;
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 8, 1, 55000.00),
(2, 2, 27, 1, 30000.00),
(3, 3, 5, 1, 50000.00),
(4, 3, 12, 1, 20000.00),
(5, 4, 31, 1, 1000.00),
(6, 5, 8, 1, 55000.00),
(7, 5, 11, 1, 15000.00),
(8, 5, 15, 1, 2500.00),
(9, 6, 5, 3, 50000.00),
(10, 6, 13, 2, 35000.00),
(11, 7, 22, 1, 9500.00),
(12, 8, 22, 1, 9500.00),
(13, 9, 18, 1, 10000.00),
(14, 10, 3, 2, 9500.00),
(15, 10, 31, 1, 1000.00),
(16, 11, 30, 2, 1000.00),
(17, 11, 27, 1, 30000.00),
(18, 12, 20, 1, 6000.00),
(19, 13, 12, 1, 20000.00),
(20, 14, 9, 1, 20000.00),
(21, 15, 25, 1, 30000.00),
(22, 15, 27, 1, 30000.00),
(23, 15, 17, 1, 15000.00),
(24, 15, 28, 1, 5000.00),
(25, 15, 21, 1, 2000.00),
(26, 16, 7, 1, 40000.00),
(27, 17, 29, 1, 250.00),
(28, 17, 14, 1, 35000.00),
(29, 17, 3, 1, 9500.00),
(30, 18, 8, 75, 55000.00),
(31, 18, 15, 1, 2500.00),
(32, 19, 31, 1, 1000.00),
(33, 20, 7, 16, 40000.00),
(34, 21, 7, 80, 40000.00),
(35, 22, 7, 2, 40000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `type` enum('COD','UPI') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Pending','Received') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `order_id`, `type`, `status`) VALUES
(1, 1, 'COD', 'Received'),
(2, 2, 'COD', 'Pending'),
(3, 3, 'COD', 'Received'),
(4, 4, 'COD', 'Received'),
(5, 5, 'COD', 'Received'),
(6, 6, 'UPI', 'Received'),
(7, 7, 'COD', 'Pending'),
(8, 8, 'UPI', 'Received'),
(9, 9, 'COD', 'Received'),
(10, 10, 'COD', 'Pending'),
(11, 11, 'COD', 'Pending'),
(12, 12, 'COD', 'Received'),
(13, 13, 'COD', 'Pending'),
(14, 14, 'COD', 'Pending'),
(15, 15, 'COD', 'Pending'),
(16, 16, 'COD', 'Pending'),
(17, 17, 'UPI', 'Received'),
(18, 18, 'COD', 'Pending'),
(19, 19, 'COD', 'Pending'),
(20, 20, 'COD', 'Pending'),
(21, 21, 'COD', 'Pending'),
(22, 22, 'COD', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `category_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `status` enum('best','normal') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `img`, `description`, `price`, `category_id`, `quantity`, `status`) VALUES
(1, 'Office Chair', 'img/uploads/Chair/1.1.jpg', 'This chair is a versatile piece of furniture designed for comfort and style. It features a sturdy frame made from high-quality materials, ensuring durability and stability. The seat is ergonomically shaped, providing optimal support for long periods of sitting. The backrest is designed to promote good posture, while the armrests (if included) offer additional comfort. Upholstered in soft, durable fabric or leather, the chair is available in various colors and patterns to complement any decor. Whether used in a dining room, office, or living space, this chair combines functionality with aesthetic appeal, making it an essential addition to any home or workspace.', 6000.00, 1, 800, 'normal'),
(3, 'Relax Bamboo Wooden Rocking Chair', 'img/uploads/Chair/chair2.jpg', 'Brand:Urbancart\r\n\r\nColour:Brown\r\n\r\nMaterial:Bamboo\r\n\r\nProduct:94D x 61W x 106H\r\n\r\nDimensions:Centimeters\r\n\r\nSize:Design-1\r\n\r\nItem Weight\r\n\r\n0.85 Pounds\r\n\r\n• Premium Bamboo Construction: Made from high-\r\n\r\nquality bamboo wood, this rocking chair boasts a natural finish that adds a warm and inviting look to any home or outdoor setting.\r\n\r\nErgonomic Design: Designed to support the\r\n\r\n• natural curve of your body, this chair features a comfortable backrest and seat that provide exceptional lumbar support and relaxation.\r\n\r\n• Smooth Rocking Motion: The chair\'s precisely\r\n\r\ncurved rocker legs provide a smooth, soothing rocking motion, helping you unwind and relax after a long day.\r\n\r\n• Versatile Indoor and Outdoor Use: Ideal for living\r\n\r\nrooms, patios, porches, balconies, gardens, and outdoor lounges, this rocking chair is designed to enhance any setting with its classic style.\r\n\r\n• Comfortable Armrests: The chair includes wide,\r\n\r\nsturdy armrests that provide added comfort, making it perfect for extended periods of sitting and relaxation.', 9500.00, 1, 94, 'normal'),
(2, 'Single Sofa', 'img/uploads/Sofa/1.jpg', 'Introducing our luxurious Single Sofa Set, a perfect blend of style, comfort, and functionality that will transform your living space into a haven of relaxation. This stunning sofa features a sleek and modern silhouette, making it an eye-catching centerpiece for any room. Upholstered in high-quality, durable fabric, it is designed to withstand the test of time while maintaining its elegant appearance.\r\n\r\nThe plush cushions are filled with premium foam, providing exceptional support and comfort for hours of lounging, whether you’re entertaining guests or enjoying a cozy movie night. With a variety of colors and textures available, you can easily find the perfect match for your home decor, from contemporary to classic styles.\r\n\r\nThe sturdy frame is crafted from solid wood, ensuring stability and durability, while the carefully designed armrests and backrest offer the ideal balance of support and relaxation. Additionally, the Single Sofa Set is easy to maintain, with removable and washable covers that make cleaning a breeze.\r\n\r\nElevate your living room with the Single Sofa Set—a sophisticated addition that combines elegance and practicality. Experience the ultimate in comfort and style; make this exquisite sofa the focal point of your home today!\r\n\r\n', 5000.00, 2, 100, 'normal'),
(4, 'Solid Wood Arm Chairs in Teal Gold', 'img/uploads/Chair/chair3.jpg', '• Primary Material: Teak Wood\r\n\r\n• Room Type: Living Room\r\n\r\n• Weight: 7 KG\r\n\r\nA variety of finishes are available for the solid Sheesham wood, allowing you to select the perfect complement to your interior.\r\n\r\nWhile the image provides a visual reference. We invite you to personalize your order to align with your desired aesthetic. To explore customization options, please contact our team directly.', 6000.00, 1, 300, 'best'),
(5, 'Zig Zag Chair', 'img/uploads/Chair/chair4.jpg', '• Featuring a unique Z-shaped structure with clean lines and deliberate absence of ornamentation, the chair showcases simplicity and distinctive design.\r\n\r\n• Crafted from four identical wooden panels, the chair challenges conventional seating, ingeniously exploring the intersection of form and function.\r\n\r\n• The Zig Zag Chair embodies contemporary design, drawing inspiration from the 20th-century De Stijl movement\'s emphasis on simplicity, geometric.\r\n\r\n• Seamlessly integrating into various interiors, the Zig Zag Chair defies its avant-garde design to become a bold focal point, adding an artistic and.\r\n\r\n• Appreciated for both functionality and artistic appeal, the Zig Zag Chair captivates those with a keen eye for innovative and timeless design.\r\n\r\n• The chair\'s planes of wood form a visually striking appearance, redefining traditional seating with bold, geometric lines.', 50000.00, 1, 4, 'best'),
(6, 'Handicrafts Wooden Armrest Chair', 'img/uploads/Chair/chair5.jpg', 'Elevate your home with this exquisite wooden seating chair, crafted from premium solid wood. Its unique design features subtly inclined legs, a curved back, and armrests, offering both elegance and comfort. The chair\'s hand-carved details and premium embossed finishing highlight the skill of the artisans, bringing a royal touch to your decor. Perfect for various settings, this chair combines traditional craftsmanship with modern aesthetics.\r\n\r\nProduct Specifications:\r\n\r\nDimensions: Height 36 inches x Width 24.5 inches\r\n\r\nDepth 24 inches\r\n\r\n• Seating Height: 16 inches\r\n\r\nMaterial: Premium Solid Wood\r\n\r\n• Color: Brown\r\n\r\nCare Instruction: Use a table cloth or a thick quality cotton cloth to wipe the chair clean.\r\n\r\n• Package Content: 1 Wooden Seating Chair', 3000.00, 1, 200, 'normal'),
(7, 'Home Decor 5 Seater Sofa', 'img/uploads/Sofa/sofa2.jpg', 'Introducing our luxurious Home Decor 5 Seater Sofa, a perfect blend of style, comfort, and functionality that will transform your living space into a haven of relaxation. This stunning sofa features a sleek and modern silhouette, making it an eye-catching centerpiece for any room. Upholstered in high-quality, durable fabric, it is designed to withstand the test of time while maintaining its elegant appearance.\r\n\r\nThe plush cushions are filled with premium foam, providing exceptional support and comfort for hours of lounging, whether you’re entertaining guests or enjoying a cozy movie night. With a variety of colors and textures available, you can easily find the perfect match for your home decor, from contemporary to classic styles.\r\n\r\nThe sturdy frame is crafted from solid wood, ensuring stability and durability, while the carefully designed armrests and backrest offer the ideal balance of support and relaxation. Additionally, the Home Decor 5 Seater Sofa is easy to maintain, with removable and washable covers that make cleaning a breeze.\r\n\r\nElevate your living room with the Home Decor 5 Seater Sofa—a sophisticated addition that combines elegance and practicality. Experience the ultimate in comfort and style; make this exquisite sofa the focal point of your home today!', 40000.00, 2, 1, 'normal'),
(8, 'Luxurious 4 Seater Sofa', 'img/uploads/Sofa/6.jpg', 'Introducing our luxurious Luxurious 4 Seater Sofa, a perfect blend of style, comfort, and functionality that will transform your living space into a haven of relaxation. This stunning sofa features a sleek and modern silhouette, making it an eye-catching centerpiece for any room. Upholstered in high-quality, durable fabric, it is designed to withstand the test of time while maintaining its elegant appearance.\r\n\r\nThe plush cushions are filled with premium foam, providing exceptional support and comfort for hours of lounging, whether you’re entertaining guests or enjoying a cozy movie night. With a variety of colors and textures available, you can easily find the perfect match for your home decor, from contemporary to classic styles.\r\n\r\nThe sturdy frame is crafted from solid wood, ensuring stability and durability, while the carefully designed armrests and backrest offer the ideal balance of support and relaxation. Additionally, the Luxurious 4 Seater Sofa is easy to maintain, with removable and washable covers that make cleaning a breeze.\r\n\r\nElevate your living room with the Luxurious 4 Seater Sofa—a sophisticated addition that combines elegance and practicality. Experience the ultimate in comfort and style; make this exquisite sofa the focal point of your home today!', 55000.00, 2, 0, 'best'),
(9, 'Recliner Sofa', 'img/uploads/Sofa/sofa3.jpg', 'This chair is a versatile piece of furniture designed for comfort and style. It features a sturdy frame made from high-quality materials, ensuring durability and stability. The seat is ergonomically shaped, providing optimal support for long periods of sitting. The backrest is designed to promote good posture, while the armrests (if included) offer additional comfort. Upholstered in soft, durable fabric or leather, the chair is available in various colors and patterns to complement any decor. Whether used in a dining room, office, or living space, this chair combines functionality with aesthetic appeal, making it an essential addition to any home or workspace', 20000.00, 2, 99, 'normal'),
(10, 'Home Decor Family Sofa', 'img/uploads/Sofa/sofa4.jpg', 'Introducing our luxurious Home Decor Family Sofa, a perfect blend of style, comfort, and functionality that will transform your living space into a haven of relaxation. This stunning sofa features a sleek and modern silhouette, making it an eye-catching centerpiece for any room. Upholstered in high-quality, durable fabric, it is designed to withstand the test of time while maintaining its elegant appearance.\r\n\r\nThe plush cushions are filled with premium foam, providing exceptional support and comfort for hours of lounging, whether you’re entertaining guests or enjoying a cozy movie night. With a variety of colors and textures available, you can easily find the perfect match for your home decor, from contemporary to classic styles.\r\n\r\nThe sturdy frame is crafted from solid wood, ensuring stability and durability, while the carefully designed armrests and backrest offer the ideal balance of support and relaxation. Additionally, the Home Decor Family Sofa is easy to maintain, with removable and washable covers that make cleaning a breeze.\r\n\r\nElevate your living room with the Home Decor Family Sofa—a sophisticated addition that combines elegance and practicality. Experience the ultimate in comfort and style; make this exquisite sofa the focal point of your home today!', 40000.00, 2, 150, 'normal'),
(11, 'DuoRest Bed', 'img/uploads/Bed/1.jpg', 'Introducing our elegant and comfortable bed, designed to be the perfect sanctuary for restful nights and rejuvenating sleep. This stunning bed features a timeless design that seamlessly blends with any decor style, from modern to traditional. Crafted with a sturdy frame and high-quality materials, it ensures durability and stability for years to come.\r\n\r\nThe plush headboard is upholstered in soft, premium fabric, providing a luxurious touch while offering support for those cozy evenings spent reading or relaxing. The bed is available in various sizes, including twin, full, queen, and king, making it suitable for any bedroom space.\r\n\r\nWith a slatted base that promotes optimal airflow and mattress support, this bed is designed to enhance your sleeping experience. The sleek lines and elegant finish add a touch of sophistication to your bedroom, creating a serene atmosphere for relaxation.\r\n\r\nEasy to assemble and maintain, our bed is not only a stylish addition to your home but also a practical choice for everyday use. Transform your bedroom into a peaceful retreat with this exquisite bed, and enjoy the ultimate in comfort and style every night.', 15000.00, 3, 298, 'best'),
(12, 'Wakefit Bed', 'img/uploads/Bed/7.jpg', 'Introducing our elegant and comfortable bed, designed to be the perfect sanctuary for restful nights and rejuvenating sleep. This stunning bed features a timeless design that seamlessly blends with any decor style, from modern to traditional. Crafted with a sturdy frame and high-quality materials, it ensures durability and stability for years to come.\r\n\r\nThe plush headboard is upholstered in soft, premium fabric, providing a luxurious touch while offering support for those cozy evenings spent reading or relaxing. The bed is available in various sizes, including twin, full, queen, and king, making it suitable for any bedroom space.\r\n\r\nWith a slatted base that promotes optimal airflow and mattress support, this bed is designed to enhance your sleeping experience. The sleek lines and elegant finish add a touch of sophistication to your bedroom, creating a serene atmosphere for relaxation.\r\n\r\nEasy to assemble and maintain, our bed is not only a stylish addition to your home but also a practical choice for everyday use. Transform your bedroom into a peaceful retreat with this exquisite bed, and enjoy the ultimate in comfort and style every night.', 20000.00, 3, 98, 'normal'),
(13, 'Home Decor Premium Bed', 'img/uploads/Bed/6.jpg', 'Introducing our elegant and comfortable bed, designed to be the perfect sanctuary for restful nights and rejuvenating sleep. This stunning bed features a timeless design that seamlessly blends with any decor style, from modern to traditional. Crafted with a sturdy frame and high-quality materials, it ensures durability and stability for years to come.\r\n\r\nThe plush headboard is upholstered in soft, premium fabric, providing a luxurious touch while offering support for those cozy evenings spent reading or relaxing. The bed is available in various sizes, including twin, full, queen, and king, making it suitable for any bedroom space.\r\n\r\nWith a slatted base that promotes optimal airflow and mattress support, this bed is designed to enhance your sleeping experience. The sleek lines and elegant finish add a touch of sophistication to your bedroom, creating a serene atmosphere for relaxation.\r\n\r\nEasy to assemble and maintain, our bed is not only a stylish addition to your home but also a practical choice for everyday use. Transform your bedroom into a peaceful retreat with this exquisite bed, and enjoy the ultimate in comfort and style every night.', 35000.00, 3, 78, 'normal'),
(14, 'Home Decor Spring Bed', 'img/uploads/Bed/5.jpg', 'Introducing our elegant and comfortable bed, designed to be the perfect sanctuary for restful nights and rejuvenating sleep. This stunning bed features a timeless design that seamlessly blends with any decor style, from modern to traditional. Crafted with a sturdy frame and high-quality materials, it ensures durability and stability for years to come.\r\n\r\nThe plush headboard is upholstered in soft, premium fabric, providing a luxurious touch while offering support for those cozy evenings spent reading or relaxing. The bed is available in various sizes, including twin, full, queen, and king, making it suitable for any bedroom space.\r\n\r\nWith a slatted base that promotes optimal airflow and mattress support, this bed is designed to enhance your sleeping experience. The sleek lines and elegant finish add a touch of sophistication to your bedroom, creating a serene atmosphere for relaxation.\r\n\r\nEasy to assemble and maintain, our bed is not only a stylish addition to your home but also a practical choice for everyday use. Transform your bedroom into a peaceful retreat with this exquisite bed, and enjoy the ultimate in comfort and style every night.', 35000.00, 3, 49, 'normal'),
(15, '5 Drawers Cabinet', 'img/uploads/Cabinet/1.jpg', 'Introducing our stylish and functional cabinet, designed to enhance your living space while providing ample storage solutions. This versatile piece features a modern design that seamlessly fits into any room, whether it’s your living room, dining area, or home office. Crafted from high-quality materials, the cabinet boasts durability and stability, ensuring it stands the test of time.\r\n\r\nThe cabinet features spacious shelves and compartments, perfect for organizing books, decorative items, or kitchen essentials. With its sleek doors, you can keep your belongings neatly tucked away, creating a clutter-free environment. The elegant finish adds a touch of sophistication, making it a beautiful addition to your home decor.\r\n\r\nAvailable in a variety of colors and styles, this cabinet can easily complement your existing furniture and enhance your overall aesthetic. Its thoughtful design includes easy-to-handle knobs and smooth hinges, ensuring effortless access to your items.\r\n\r\nEasy to assemble and maintain, our cabinet is not only a practical storage solution but also a stylish statement piece. Elevate your home organization with this exquisite cabinet, and enjoy the perfect blend of form and function in your living space.', 2500.00, 7, 198, 'best'),
(16, '2 Door Cabinet', 'img/uploads/Cabinet/2.jpg', 'Introducing our stylish and functional cabinet, designed to enhance your living space while providing ample storage solutions. This versatile piece features a modern design that seamlessly fits into any room, whether it’s your living room, dining area, or home office. Crafted from high-quality materials, the cabinet boasts durability and stability, ensuring it stands the test of time.\r\n\r\nThe cabinet features spacious shelves and compartments, perfect for organizing books, decorative items, or kitchen essentials. With its sleek doors, you can keep your belongings neatly tucked away, creating a clutter-free environment. The elegant finish adds a touch of sophistication, making it a beautiful addition to your home decor.\r\n\r\nAvailable in a variety of colors and styles, this cabinet can easily complement your existing furniture and enhance your overall aesthetic. Its thoughtful design includes easy-to-handle knobs and smooth hinges, ensuring effortless access to your items.\r\n\r\nEasy to assemble and maintain, our cabinet is not only a practical storage solution but also a stylish statement piece. Elevate your home organization with this exquisite cabinet, and enjoy the perfect blend of form and function in your living space.', 4000.00, 7, 100, 'normal'),
(17, 'Luxurious Cabinet', 'img/uploads/Cabinet/3.jpg', 'Introducing our stylish and functional cabinet, designed to enhance your living space while providing ample storage solutions. This versatile piece features a modern design that seamlessly fits into any room, whether it’s your living room, dining area, or home office. Crafted from high-quality materials, the cabinet boasts durability and stability, ensuring it stands the test of time.\r\n\r\nThe cabinet features spacious shelves and compartments, perfect for organizing books, decorative items, or kitchen essentials. With its sleek doors, you can keep your belongings neatly tucked away, creating a clutter-free environment. The elegant finish adds a touch of sophistication, making it a beautiful addition to your home decor.\r\n\r\nAvailable in a variety of colors and styles, this cabinet can easily complement your existing furniture and enhance your overall aesthetic. Its thoughtful design includes easy-to-handle knobs and smooth hinges, ensuring effortless access to your items.\r\n\r\nEasy to assemble and maintain, our cabinet is not only a practical storage solution but also a stylish statement piece. Elevate your home organization with this exquisite cabinet, and enjoy the perfect blend of form and function in your living space.', 15000.00, 7, 84, 'normal'),
(18, 'Home Decor Special Cabinet', 'img/uploads/Cabinet/4.jpg', 'Introducing our stylish and functional cabinet, designed to enhance your living space while providing ample storage solutions. This versatile piece features a modern design that seamlessly fits into any room, whether it’s your living room, dining area, or home office. Crafted from high-quality materials, the cabinet boasts durability and stability, ensuring it stands the test of time.\r\n\r\nThe cabinet features spacious shelves and compartments, perfect for organizing books, decorative items, or kitchen essentials. With its sleek doors, you can keep your belongings neatly tucked away, creating a clutter-free environment. The elegant finish adds a touch of sophistication, making it a beautiful addition to your home decor.\r\n\r\nAvailable in a variety of colors and styles, this cabinet can easily complement your existing furniture and enhance your overall aesthetic. Its thoughtful design includes easy-to-handle knobs and smooth hinges, ensuring effortless access to your items.\r\n\r\nEasy to assemble and maintain, our cabinet is not only a practical storage solution but also a stylish statement piece. Elevate your home organization with this exquisite cabinet, and enjoy the perfect blend of form and function in your living space.', 10000.00, 7, 49, 'normal'),
(19, '4 Layer Bookcase', 'img/uploads/Bookcase/1.jpg', 'Discover the perfect blend of style and practicality with our unique bookcase, designed to be more than just a storage solution—it\'s a statement piece for your home. This beautifully crafted bookcase features an innovative design that combines open and closed shelving, allowing you to showcase your favorite books, decorative items, and personal treasures while keeping your space organized.\r\n\r\nConstructed from high-quality materials, this bookcase is both sturdy and stylish, ensuring it can hold your collection with ease. The distinctive layout of shelves creates an eye-catching visual effect, making it a stunning addition to any room, whether it’s your living room, home office, or reading nook.\r\n\r\nAvailable in a range of finishes, from natural wood to sleek modern hues, this bookcase can effortlessly complement your existing decor. Its versatile design allows you to arrange books and decor in a way that reflects your personal style, while the thoughtful craftsmanship ensures durability and longevity.\r\n\r\nEasy to assemble and maintain, our unique bookcase is not just functional; it’s an artistic expression that enhances your living space. Elevate your home with this exceptional bookcase and create a stylish environment that inspires reading and creativity.', 1000.00, 6, 200, 'normal'),
(20, 'Modern Bookcase', 'img/uploads/Bookcase/3.jpg', 'Discover the perfect blend of style and practicality with our unique bookcase, designed to be more than just a storage solution—it\'s a statement piece for your home. This beautifully crafted bookcase features an innovative design that combines open and closed shelving, allowing you to showcase your favorite books, decorative items, and personal treasures while keeping your space organized.\r\n\r\nConstructed from high-quality materials, this bookcase is both sturdy and stylish, ensuring it can hold your collection with ease. The distinctive layout of shelves creates an eye-catching visual effect, making it a stunning addition to any room, whether it’s your living room, home office, or reading nook.\r\n\r\nAvailable in a range of finishes, from natural wood to sleek modern hues, this bookcase can effortlessly complement your existing decor. Its versatile design allows you to arrange books and decor in a way that reflects your personal style, while the thoughtful craftsmanship ensures durability and longevity.\r\n\r\nEasy to assemble and maintain, our unique bookcase is not just functional; it’s an artistic expression that enhances your living space. Elevate your home with this exceptional bookcase and create a stylish environment that inspires reading and creativity.\r\n\r\n', 6000.00, 6, 99, 'normal'),
(21, 'Luxurious Bookcase', 'img/uploads/Bookcase/4.jpg', 'Discover the perfect blend of style and practicality with our unique bookcase, designed to be more than just a storage solution—it\'s a statement piece for your home. This beautifully crafted bookcase features an innovative design that combines open and closed shelving, allowing you to showcase your favorite books, decorative items, and personal treasures while keeping your space organized.\r\n\r\nConstructed from high-quality materials, this bookcase is both sturdy and stylish, ensuring it can hold your collection with ease. The distinctive layout of shelves creates an eye-catching visual effect, making it a stunning addition to any room, whether it’s your living room, home office, or reading nook.\r\n\r\nAvailable in a range of finishes, from natural wood to sleek modern hues, this bookcase can effortlessly complement your existing decor. Its versatile design allows you to arrange books and decor in a way that reflects your personal style, while the thoughtful craftsmanship ensures durability and longevity.\r\n\r\nEasy to assemble and maintain, our unique bookcase is not just functional; it’s an artistic expression that enhances your living space. Elevate your home with this exceptional bookcase and create a stylish environment that inspires reading and creativity.\r\n', 2000.00, 6, 79, 'normal'),
(22, 'Loving Swan Bookcase', 'img/uploads/Bookcase/b1.jpg', 'Discover the perfect blend of style and practicality with our unique bookcase, designed to be more than just a storage solution—it\'s a statement piece for your home. This beautifully crafted bookcase features an innovative design that combines open and closed shelving, allowing you to showcase your favorite books, decorative items, and personal treasures while keeping your space organized.\r\n\r\nConstructed from high-quality materials, this bookcase is both sturdy and stylish, ensuring it can hold your collection with ease. The distinctive layout of shelves creates an eye-catching visual effect, making it a stunning addition to any room, whether it’s your living room, home office, or reading nook.\r\n\r\nAvailable in a range of finishes, from natural wood to sleek modern hues, this bookcase can effortlessly complement your existing decor. Its versatile design allows you to arrange books and decor in a way that reflects your personal style, while the thoughtful craftsmanship ensures durability and longevity.\r\n\r\nEasy to assemble and maintain, our unique bookcase is not just functional; it’s an artistic expression that enhances your living space. Elevate your home with this exceptional bookcase and create a stylish environment that inspires reading and creativity.', 9500.00, 6, 98, 'best'),
(23, 'Home Decor Special Dinning Table', 'img/uploads/Dinning Table/1.1.jpg', 'Introducing our exquisite dining table, a perfect centerpiece for your dining area that combines elegance, functionality, and durability. Crafted from high-quality materials, this table is designed to withstand the rigors of daily use while adding a touch of sophistication to your home.\r\n\r\nWith its spacious surface, this dining table comfortably accommodates family gatherings, dinner parties, or casual meals, making it an ideal choice for both intimate dinners and festive celebrations. The sleek design features clean lines and a modern aesthetic, allowing it to seamlessly blend with any decor style, from contemporary to traditional.\r\n\r\nAvailable in a variety of finishes, including rich wood tones and chic painted options, you can easily find the perfect match for your existing furniture. The sturdy construction ensures stability, while the thoughtfully designed legs provide ample legroom for all your guests.\r\n\r\nEasy to assemble and maintain, our dining table is not just a piece of furniture; it’s a gathering place for memories, laughter, and shared meals. Elevate your dining experience with this stunning table, and create a warm and inviting atmosphere in your home.', 30000.00, 5, 298, 'best'),
(24, 'Italiana Dinning Table', 'img/uploads/Dinning Table/2.jpg', 'Introducing our exquisite dining table, a perfect centerpiece for your dining area that combines elegance, functionality, and durability. Crafted from high-quality materials, this table is designed to withstand the rigors of daily use while adding a touch of sophistication to your home.\r\n\r\nWith its spacious surface, this dining table comfortably accommodates family gatherings, dinner parties, or casual meals, making it an ideal choice for both intimate dinners and festive celebrations. The sleek design features clean lines and a modern aesthetic, allowing it to seamlessly blend with any decor style, from contemporary to traditional.\r\n\r\nAvailable in a variety of finishes, including rich wood tones and chic painted options, you can easily find the perfect match for your existing furniture. The sturdy construction ensures stability, while the thoughtfully designed legs provide ample legroom for all your guests.\r\n\r\nEasy to assemble and maintain, our dining table is not just a piece of furniture; it’s a gathering place for memories, laughter, and shared meals. Elevate your dining experience with this stunning table, and create a warm and inviting atmosphere in your home.\r\n\r\n', 25000.00, 5, 150, 'normal'),
(26, '3 Door Wardrobe', 'img/uploads/Wardrobe/1.1.jpg', 'Introducing our stylish and functional wardrobe, designed to bring organization and elegance to your bedroom. This beautifully crafted wardrobe offers ample storage space for your clothing, accessories, and personal items, making it the perfect solution for keeping your space tidy and clutter-free.\r\n\r\nConstructed from high-quality materials, this wardrobe features a sturdy frame and durable doors that ensure long-lasting use. The interior is thoughtfully designed with a combination of hanging space, shelves, and drawers, allowing you to easily organize your wardrobe essentials while maximizing storage efficiency.\r\n\r\nThe sleek and modern design of the wardrobe adds a touch of sophistication to any bedroom decor, whether contemporary, classic, or eclectic. Available in a variety of finishes, from warm wood tones to chic painted options, you can find the perfect match to complement your existing furniture.\r\n\r\nWith easy-to-handle knobs and smooth hinges, accessing your belongings is a breeze. The wardrobe is also easy to assemble and maintain, making it a practical addition to your home.\r\n\r\nTransform your bedroom into a serene and organized retreat with our exquisite wardrobe, where style meets functionality for a truly elevated living experience.', 12000.00, 8, 300, 'normal'),
(27, 'Luxurious Wardrobe', 'img/uploads/Wardrobe/2.jpg', 'Introducing our stylish and functional wardrobe, designed to bring organization and elegance to your bedroom. This beautifully crafted wardrobe offers ample storage space for your clothing, accessories, and personal items, making it the perfect solution for keeping your space tidy and clutter-free.\r\n\r\nConstructed from high-quality materials, this wardrobe features a sturdy frame and durable doors that ensure long-lasting use. The interior is thoughtfully designed with a combination of hanging space, shelves, and drawers, allowing you to easily organize your wardrobe essentials while maximizing storage efficiency.\r\n\r\nThe sleek and modern design of the wardrobe adds a touch of sophistication to any bedroom decor, whether contemporary, classic, or eclectic. Available in a variety of finishes, from warm wood tones to chic painted options, you can find the perfect match to complement your existing furniture.\r\n\r\nWith easy-to-handle knobs and smooth hinges, accessing your belongings is a breeze. The wardrobe is also easy to assemble and maintain, making it a practical addition to your home.\r\n\r\nTransform your bedroom into a serene and organized retreat with our exquisite wardrobe, where style meets functionality for a truly elevated living experience.\r\n\r\n', 30000.00, 8, 84, 'best'),
(28, '2 Door Wardrobe', 'img/uploads/Wardrobe/4.jpg', 'Introducing our stylish and functional wardrobe, designed to bring organization and elegance to your bedroom. This beautifully crafted wardrobe offers ample storage space for your clothing, accessories, and personal items, making it the perfect solution for keeping your space tidy and clutter-free.\r\n\r\nConstructed from high-quality materials, this wardrobe features a sturdy frame and durable doors that ensure long-lasting use. The interior is thoughtfully designed with a combination of hanging space, shelves, and drawers, allowing you to easily organize your wardrobe essentials while maximizing storage efficiency.\r\n\r\nThe sleek and modern design of the wardrobe adds a touch of sophistication to any bedroom decor, whether contemporary, classic, or eclectic. Available in a variety of finishes, from warm wood tones to chic painted options, you can find the perfect match to complement your existing furniture.\r\n\r\nWith easy-to-handle knobs and smooth hinges, accessing your belongings is a breeze. The wardrobe is also easy to assemble and maintain, making it a practical addition to your home.\r\n\r\nTransform your bedroom into a serene and organized retreat with our exquisite wardrobe, where style meets functionality for a truly elevated living experience.\r\n\r\n', 5000.00, 8, 199, 'normal'),
(29, 'Mini Study Table', 'img/uploads/Table/1.1.jpg', 'Introducing our versatile table, a perfect blend of style and functionality that enhances any space in your home. Whether you need a dining table, a workspace, or a decorative accent, this table is designed to meet your needs while adding a touch of elegance to your decor.\r\n\r\nCrafted from high-quality materials, this table boasts a sturdy construction that ensures durability and stability for everyday use. Its spacious surface provides ample room for dining, working, or displaying your favorite decor items, making it an ideal choice for both casual and formal settings.\r\n\r\nThe sleek design features clean lines and a modern aesthetic, allowing it to seamlessly fit into any interior style, from contemporary to rustic. Available in a variety of finishes, including rich wood tones and chic painted options, you can easily find the perfect match for your existing furniture.\r\n\r\nEasy to assemble and maintain, this table is not just a functional piece; it’s a stylish addition that brings people together. Elevate your home with our exquisite table, and create a welcoming atmosphere for family gatherings, meals, or productive work sessions.', 250.00, 4, 298, 'normal'),
(30, 'Study Table', 'img/uploads/Table/5.jpg', 'Introducing our versatile table, a perfect blend of style and functionality that enhances any space in your home. Whether you need a dining table, a workspace, or a decorative accent, this table is designed to meet your needs while adding a touch of elegance to your decor.\r\n\r\nCrafted from high-quality materials, this table boasts a sturdy construction that ensures durability and stability for everyday use. Its spacious surface provides ample room for dining, working, or displaying your favorite decor items, making it an ideal choice for both casual and formal settings.\r\n\r\nThe sleek design features clean lines and a modern aesthetic, allowing it to seamlessly fit into any interior style, from contemporary to rustic. Available in a variety of finishes, including rich wood tones and chic painted options, you can easily find the perfect match for your existing furniture.\r\n\r\nEasy to assemble and maintain, this table is not just a functional piece; it’s a stylish addition that brings people together. Elevate your home with our exquisite table, and create a welcoming atmosphere for family gatherings, meals, or productive work sessions.', 1000.00, 4, 150, 'normal'),
(31, 'Coffee Table', 'img/uploads/Table/3.jpg', 'Introducing our versatile table, a perfect blend of style and functionality that enhances any space in your home. Whether you need a dining table, a workspace, or a decorative accent, this table is designed to meet your needs while adding a touch of elegance to your decor.\r\n\r\nCrafted from high-quality materials, this table boasts a sturdy construction that ensures durability and stability for everyday use. Its spacious surface provides ample room for dining, working, or displaying your favorite decor items, making it an ideal choice for both casual and formal settings.\r\n\r\nThe sleek design features clean lines and a modern aesthetic, allowing it to seamlessly fit into any interior style, from contemporary to rustic. Available in a variety of finishes, including rich wood tones and chic painted options, you can easily find the perfect match for your existing furniture.\r\n\r\nEasy to assemble and maintain, this table is not just a functional piece; it’s a stylish addition that brings people together. Elevate your home with our exquisite table, and create a welcoming atmosphere for family gatherings, meals, or productive work sessions.', 1000.00, 4, 194, 'best'),
(32, 'Home Decor Special Sofa', 'img/uploads/Sofa/hdssofa.jpg', 'Introducing our luxurious Home Decor 5 Seater Sofa, a perfect blend of style, comfort, and functionality that will transform your living space into a haven of relaxation. This stunning sofa features a sleek and modern silhouette, making it an eye-catching centerpiece for any room. Upholstered in high-quality, durable fabric, it is designed to withstand the test of time while maintaining its elegant appearance. The plush cushions are filled with premium foam, providing exceptional support and comfort for hours of lounging, whether you’re entertaining guests or enjoying a cozy movie night. With a variety of colors and textures available, you can easily find the perfect match for your home decor, from contemporary to classic styles. The sturdy frame is crafted from solid wood, ensuring stability and durability, while the carefully designed armrests and backrest offer the ideal balance of support and relaxation. Additionally, the Home Decor 5 Seater Sofa is easy to maintain, with removable and washable covers that make cleaning a breeze. Elevate your living room with the Home Decor 5 Seater Sofa—a sophisticated addition that combines elegance and practicality. Experience the ultimate in comfort and style; make this exquisite sofa the focal point of your home today!', 70599.00, 2, 100, 'normal');

--
-- Triggers `product`
--
DROP TRIGGER IF EXISTS `adjust_cart_quantity_after_product_update`;
DELIMITER $$
CREATE TRIGGER `adjust_cart_quantity_after_product_update` AFTER UPDATE ON `product` FOR EACH ROW BEGIN
    IF NEW.quantity <> OLD.quantity THEN
        -- Update cart quantities to not exceed new product quantity
        UPDATE cart
        SET quantity = LEAST(quantity, NEW.quantity)
        WHERE product_id = NEW.id;
    END IF;
END
$$
DELIMITER ;

DELIMITER $$
--
-- Events
--
DROP EVENT IF EXISTS `cleanup_old_cart_records`$$
CREATE DEFINER=`root`@`localhost` EVENT `cleanup_old_cart_records` ON SCHEDULE EVERY 1 WEEK STARTS '2025-05-10 12:25:15' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM cart
  WHERE add_on <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
