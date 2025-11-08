-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 29, 2025 at 08:45 AM
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
-- Table structure for table `billing_details`
--

CREATE TABLE `billing_details` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `pincode` mediumint(6) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing_details`
--

INSERT INTO `billing_details` (`id`, `fname`, `lname`, `address`, `city`, `pincode`, `email`, `phone_no`) VALUES
(15, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(16, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(17, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(18, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(19, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, '', 0),
(20, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, '', 0),
(21, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(22, '', '', '												', '', 0, '', 0),
(23, '', '', '												', '', 0, '', 0),
(24, '', '', '												', '', 0, '', 0),
(25, '', '', '												', '', 0, '', 0),
(26, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(27, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(28, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`, `thumbnail`) VALUES
(8, 'demo1', './UploadImg/ProductImg/1753258785_Children day.jpg'),
(9, 'demo', './UploadImg/ProductImg/1753258978_wallpapaer2.jpg\n'),
(10, 'demo5', './UploadImg/CategoryImg/68b0684de15889.50176699.jpg'),
(13, 'dummy1', './UploadImg/CategoryImg/68b069ef902fa3.37089473.jpg'),
(14, 'dummy2', './UploadImg/CategoryImg/68b069ef902fa3.37089473.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `coupon_name` varchar(255) NOT NULL,
  `coupon_code` varchar(100) NOT NULL,
  `discount_type` enum('Percentage','Rs') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`id`, `coupon_name`, `coupon_code`, `discount_type`, `amount`, `description`, `status`, `created_at`) VALUES
(1, 'Rk', 'RKD123', '', 1000.00, 'August Sale', 1, '2025-07-30 17:50:22'),
(2, 'Rk2', 'RKD12', 'Percentage', 15.00, 'August new sale', 1, '2025-07-30 17:52:28'),
(3, 'Rkd', '1234', 'Rs', 62677.00, 'August arrival', 1, '2025-07-30 18:12:15'),
(4, 'Rk_New', 'RKD@123', 'Percentage', 1000.00, 'this is coupon', 0, '2025-08-07 06:02:42'),
(5, 'Rk', 'RKD', 'Percentage', 1000.00, 'my coupon', 0, '2025-08-07 06:50:12');

-- --------------------------------------------------------

--
-- Table structure for table `navbar`
--

CREATE TABLE `navbar` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navbar`
--

INSERT INTO `navbar` (`id`, `category_id`) VALUES
(11, 9),
(12, 10),
(13, 13),
(14, 14);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `shipping_details_id` int(11) NOT NULL,
  `billing_details_id` int(11) NOT NULL,
  `payment_mode` int(11) NOT NULL,
  `total` double(25,3) NOT NULL,
  `order_status` tinyint(1) NOT NULL DEFAULT 0,
  `applied_coupon_id` int(11) NOT NULL,
  `purchase_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `shipping_details_id`, `billing_details_id`, `payment_mode`, `total`, `order_status`, `applied_coupon_id`, `purchase_date`) VALUES
(3, 15, 15, 1, 65000.000, 2, 2, 1754930876),
(4, 16, 16, 0, 65000.000, 2, 2, 1754978218),
(5, 17, 17, 1, 65000.000, 0, 2, 1754978657),
(6, 18, 18, 0, 0.000, 1, 2, 1754978818),
(7, 19, 19, 0, 195000.000, 1, 2, 1754979342),
(8, 20, 20, 0, 195000.000, 2, 1, 1754979935),
(9, 21, 21, 1, 65000.000, 0, 2, 1754987146),
(10, 22, 22, 1, 65000.000, 2, 2, 1754987996),
(11, 23, 23, 0, 65000.000, 2, 0, 1754988010),
(12, 24, 24, 0, 195000.000, 2, 0, 1754988021),
(13, 25, 25, 0, 195000.000, 2, 0, 1754988109),
(14, 26, 26, 0, 130000.000, 1, 1, 1755012292),
(15, 27, 27, 0, 130000.000, 3, 0, 1755012565),
(16, 28, 28, 1, 130000.000, 0, 2, 1755064875);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `orderId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subTotal` double(25,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`orderId`, `productId`, `quantity`, `subTotal`) VALUES
(10, 9, 1, 65000.000),
(11, 9, 1, 65000.000),
(12, 6, 1, 65000.000),
(12, 7, 1, 65000.000),
(12, 9, 1, 65000.000),
(13, 6, 1, 65000.000),
(13, 7, 1, 65000.000),
(13, 9, 1, 65000.000),
(14, 8, 1, 65000.000),
(14, 9, 1, 65000.000),
(15, 8, 1, 65000.000),
(15, 9, 1, 65000.000),
(16, 6, 1, 65000.000),
(16, 8, 1, 65000.000);

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

-- --------------------------------------------------------

--
-- Table structure for table `productimg`
--

CREATE TABLE `productimg` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productimg`
--

INSERT INTO `productimg` (`id`, `product_id`, `image_path`) VALUES
(5, 6, './ProductImg/MultiImg/1753252811_gallery_0_Children day.jpg'),
(6, 7, './UploadImg/ProductImg/MultiImg/1753253158_gallery_0_Children day.jpg'),
(7, 8, './UploadImg/ProductImg/MultiImg/1753253426_gallery_0_Children day.jpg'),
(8, 9, './UploadImg/ProductImg/MultiImg/1753258978_gallery_0_im3.jpeg'),
(9, 9, './UploadImg/ProductImg/MultiImg/1753258978_gallery_1_img1.jpg'),
(10, 9, './UploadImg/ProductImg/MultiImg/1753258978_gallery_2_img4.jpg'),
(11, 8, './UploadImg/ProductImg/MultiImg/1756021243_download.png'),
(12, 8, './UploadImg/ProductImg/MultiImg/1756021243_php.jpg'),
(13, 8, './UploadImg/ProductImg/MultiImg/1756021243_php1.jpg'),
(14, 6, './UploadImg/ProductImg/MultiImg/1756021974_php4.jpg');

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
(14, 10, './UploadImg/ShippingImg/10_1755676227.jpg', 0, 55250, 'Post', '2025-08-20'),
(15, 3, './UploadImg/ShippingImg/3_1755756943.jpg', 350, 0, 'Post', '2025-09-04'),
(16, 4, './UploadImg/ShippingImg/4_1755755950.jpg', 250, 0, '', '2025-09-06'),
(17, 8, './UploadImg/ShippingImg/8_1755757224.jpg', 500, -1000, 'Courier', '2025-08-28');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_details`
--

CREATE TABLE `shipping_details` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `pincode` mediumint(6) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_details`
--

INSERT INTO `shipping_details` (`id`, `fname`, `lname`, `address`, `city`, `pincode`, `email`, `phone_no`) VALUES
(15, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(16, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(17, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'rkd@gmail.com', 72672618162),
(18, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(19, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, '', 0),
(20, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, '', 0),
(21, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392),
(22, '', '', '														', '', 0, '', 0),
(23, '', '', '														', '', 0, '', 0),
(24, '', '', '														', '', 0, '', 0),
(25, '', '', '														', '', 0, '', 0),
(26, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'rkd@gmail.com', 72672618162),
(27, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'rkd@gmail.com', 72672618162),
(28, 'Rupesh', 'Dash', 'KOTE,CHANNAPURA ROAD\r\nNEAR SHIVAM PROVISIONAL STORE', 'CHIKMAGALUR', 577101, 'r@gmil', 8283928392);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `storeName` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `storeName`, `address`, `contact`, `email`) VALUES
(1, 'daya', 'kote', '7665656767', 'da@gmail.com'),
(2, 'dayal', 'kote', '7676869799', 'd@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `sub_category` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`id`, `sub_category`, `category_id`) VALUES
(2, 'dummy1', 9),
(5, 'dummy1', 8),
(6, 'dummy', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing_details`
--
ALTER TABLE `billing_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `navbar`
--
ALTER TABLE `navbar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `billing_details_id` (`billing_details_id`),
  ADD KEY `shipping_details_id` (`shipping_details_id`),
  ADD KEY `applied_coupon_id` (`applied_coupon_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD KEY `orderId` (`orderId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `sub_category_id` (`sub_category_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `productimg`
--
ALTER TABLE `productimg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_order_relation` (`orderId`);

--
-- Indexes for table `shipping_details`
--
ALTER TABLE `shipping_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing_details`
--
ALTER TABLE `billing_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `navbar`
--
ALTER TABLE `navbar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `productimg`
--
ALTER TABLE `productimg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `shipping_details`
--
ALTER TABLE `shipping_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `navbar`
--
ALTER TABLE `navbar`
  ADD CONSTRAINT `navbar_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`sub_category_id`) REFERENCES `subcategory` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `productimg`
--
ALTER TABLE `productimg`
  ADD CONSTRAINT `productimg_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  ADD CONSTRAINT `shipping_order_relation` FOREIGN KEY (`orderId`) REFERENCES `order` (`id`);

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
