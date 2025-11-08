-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 29, 2025 at 08:36 AM
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
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `MRP` decimal(10,3) DEFAULT NULL,
  `SP` decimal(10,3) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `create` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `productName`, `description`, `thumbnail`, `category_id`, `sub_category_id`, `store_id`, `MRP`, `SP`, `status`, `create`) VALUES
(6, 'product-1', '', './UploadImg/ProductImg/1753258785_Children day.jpg', 8, 2, 1, 56000.000, 65000.000, 1, '2025-08-25 11:49:56'),
(7, 'product-2', '', './UploadImg/ProductImg/1753258616_Children day.jpg', 9, 5, 2, 56000.000, 65000.000, 1, '2025-08-25 11:49:56'),
(8, 'product-3', '', './UploadImg/ProductImg/1753255319_1693978575679.jpg', 8, 6, 1, 56000.000, 65000.000, 1, '2025-08-25 11:49:56'),
(9, 'product-4', '  Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo velit quis dolorum aperiam. Veniam iure facilis reiciendis, a, distinctio, sint exercitationem delectus ut animi officiis sapiente non fugiat perferendis rem?', './UploadImg/ProductImg/1753258978_wallpapaer2.jpg', 9, 6, 2, 56000.000, 65000.000, 1, '2025-08-25 11:49:56'),
(10, '  Product-5', 'Lorem ipsum dolor sit amet consectetur adipisici..', './UploadImg/ProductImg/1753258978_wallpapaer2.jpg', 10, 2, 2, 5000.000, 6000.000, 1, '2025-08-25 13:07:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `sub_category_id` (`sub_category_id`),
  ADD KEY `store_id` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`sub_category_id`) REFERENCES `subcategory` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
