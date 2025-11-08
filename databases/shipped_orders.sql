-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 21, 2025 at 07:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_commerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `shipped_orders`
--

CREATE TABLE `shipped_orders` (
  `id` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `charges` float NOT NULL DEFAULT 80,
  `total_charges` float NOT NULL,
  `shipping_mode` varchar(25) NOT NULL,
  `date_of_shipping` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipped_orders`
--

INSERT INTO `shipped_orders` (`id`, `orderId`, `image_url`, `charges`, `total_charges`, `shipping_mode`, `date_of_shipping`) VALUES
(3, 6, '', 0, 0, 'Post', '2025-08-20'),
(4, 14, './UploadImg/ShippingImg/14_1755678198.jpeg', 850, 129000, 'Post', '2025-09-05'),
(5, 11, './UploadImg/ShippingImg/1755668270_wallpapaer2.jpg', 120, 65000, 'Post', '2025-08-20'),
(6, 12, './UploadImg/ShippingImg/1755668459_Children day.jpg', 120, 195000, 'Post', '2025-08-28'),
(9, 16, './UploadImg/ShippingImg/16_1755675085.jpg', 900, 110500, 'Courier', '2025-08-20'),
(11, 7, './UploadImg/ShippingImg/7_1755673986.jpg', 500, 0, 'Post', '2025-08-20'),
(14, 10, './UploadImg/ShippingImg/10_1755676227.jpg', 600, 55250, 'Post', '2025-08-20'),
(15, 3, './UploadImg/ShippingImg/3_1755676430.jpg', 90, 0, 'Post', '2025-08-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_order_relation` (`orderId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  ADD CONSTRAINT `shipping_order_relation` FOREIGN KEY (`orderId`) REFERENCES `order` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
