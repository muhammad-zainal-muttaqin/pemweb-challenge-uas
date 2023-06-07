-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2023 at 08:32 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_webjualbeli`
--

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 1001, 1, 2),
(2, 1001, 2, 1),
(3, 1002, 3, 3),
(4, 1004, 1, 5),
(5, 1004, 2, 4),
(6, 1004, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_number` int(11) NOT NULL,
  `order_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_number`, `order_date`, `status`, `user_id`) VALUES
(1001, '2023-05-30', 'Shipped', 1),
(1002, '2023-05-29', 'Delivered', 2),
(1003, '2023-05-28', 'Cancelled', 1),
(1004, '2023-05-27', 'Shipped', 3),
(1005, '2023-05-26', 'Shipped', 2),
(1006, '2023-05-25', 'Pending', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`) VALUES
(1, 'Product 1', 'Description for Product 1', 19.99, 8, 0x75706c6f6164732f38696d616765732e6a7067),
(2, 'Product 2', 'Description for Product 2', 29.99, 2, 0x75706c6f6164732f32646f776e6c6f61642e6a7067),
(3, 'Product 3', 'Description for Product 3', 39.99, 8, 0x75706c6f6164732f33646f776e6c6f61642e6a7067),
(4, 'Product 3', 'Description for Product 3', 49.99, 8, 0x75706c6f6164732f37696d616765732e6a7067),
(5, 'Product 5', 'Description for Product 5', 39.99, 8, 0x75706c6f6164732f3130696d616765732e6a7067),
(6, 'Product 6', 'Description for Product 6', 49.99, 12, 0x75706c6f6164732f3131696d616765732e6a7067),
(7, 'Product 7', 'Description for Product 7', 29.99, 15, 0x75706c6f6164732f36696d616765732e6a7067),
(8, 'Product 8', 'Description for Product 8', 19.99, 20, 0x75706c6f6164732f38696d616765732e6a7067),
(9, 'Product 9', 'Description for Product 9', 24.99, 5, 0x75706c6f6164732f34696d616765732e6a7067),
(10, 'Product 10', 'Description for Product 10', 14.99, 18, 0x75706c6f6164732f36696d616765732e6a7067),
(11, 'Product 11', 'Description for Product 11', 9.99, 10, 0x75706c6f6164732f3131696d616765732e6a7067),
(12, 'Product 12', 'Description for Product 12', 34.99, 3, 0x75706c6f6164732f34696d616765732e6a7067),
(13, 'Product 13', 'Description for Product 13', 39.99, 6, 0x75706c6f6164732f31646f776e6c6f61642e6a7067),
(14, 'Product 14', 'Description for Product 14', 29.99, 15, 0x75706c6f6164732f32646f776e6c6f61642e6a7067),
(15, 'Product 15', 'Description for Product 15', 19.99, 12, 0x75706c6f6164732f34696d616765732e6a7067),
(16, 'Product 16', 'Description for Product 16', 24.99, 9, 0x75706c6f6164732f39696d616765732e6a7067),
(17, 'Product 17', 'Description for Product 17', 14.99, 4, 0x75706c6f6164732f36696d616765732e6a7067),
(18, 'Product 18', 'Description for Product 18', 9.99, 10, 0x75706c6f6164732f3131696d616765732e6a7067),
(19, 'Product 19', 'Description for Product 19', 39.99, 7, 0x75706c6f6164732f34696d616765732e6a7067),
(20, 'Product 20', 'Description for Product 20', 49.99, 11, 0x75706c6f6164732f3131696d616765732e6a7067),
(21, 'Product 21', 'Description for Product 21', 29.99, 15, 0x75706c6f6164732f37696d616765732e6a7067),
(22, 'Product 22', 'Description for Product 22', 19.99, 22, 0x75706c6f6164732f35696d616765732e6a7067),
(23, 'Product 23', 'Description for Product 23', 24.99, 5, 0x75706c6f6164732f33646f776e6c6f61642e6a7067),
(24, 'Product 24', 'Description for Product 24', 14.99, 9, 0x75706c6f6164732f36696d616765732e6a7067),
(25, 'Product 25', 'Description for Product 25', 9.99, 14, 0x75706c6f6164732f38696d616765732e6a7067),
(28, 'Product 26', 'Description for Product 26', 26.00, 26, 0x75706c6f6164732f3131696d616765732e6a7067),
(30, 'Product 28', 'Product 28', 88.00, 12, 0x75706c6f6164732f3131696d616765732e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `registration_date`) VALUES
(1, 'zyn', '$2y$10$fUMiNC3Tu6VbQ1N4oroZG.Fwyus2nvzQzlRRrIH7BjkvVeoIJYGaa', 'zyn@gmail.com', '2023-05-29'),
(2, 'zainalmtq2010', '$2y$10$AbrcA4xbTR1Yb3UAYnJLSuDRdb0MvqTnTMPZxDKZdmvf25MrebFFC', 'zyn2@gmail.com', '2023-05-29'),
(3, 'zainaladmin', '$2y$10$rXpW0DNyAfGo5YIse1CuJeVeCzf7nfsiapC1.pGxNtsqADE18BeSe', 'zyn3@gmail.com', '2023-05-30'),
(5, 'Dummyadalah3', '$2y$10$8idZhu/XY8a6e7v0zDojoOq2/UFCUCexiu1d5oP1n5c7Z.bp5CzlG', 'Dummy3@gmail.com', '2023-06-06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `orderdetails_ibfk_2` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_number`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
