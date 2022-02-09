-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 10, 2022 at 12:47 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paint`
--

-- --------------------------------------------------------

--
-- Table structure for table `pt_admin`
--

CREATE TABLE `pt_admin` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(100) NOT NULL,
  `picture` varchar(50) NOT NULL DEFAULT 'default.jpg',
  `status` int(11) NOT NULL DEFAULT 1,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pt_admin`
--

INSERT INTO `pt_admin` (`id`, `fname`, `email`, `phone`, `password`, `picture`, `status`, `date`) VALUES
(1, 'Akubue Administrator', 'akubueaugustuskc@gmail.com', '08081301066', '$2y$11$wQc4O5veGv/Ktohq5rSThe3BX8puStw/evZ8rl5ShNtha806ooxF.', 'img_pt_02994193e9.jpg', 1, '2021-02-18 01:27:51');

-- --------------------------------------------------------

--
-- Table structure for table `pt_categories`
--

CREATE TABLE `pt_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `img_name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pt_contact`
--

CREATE TABLE `pt_contact` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(535) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pt_products`
--

CREATE TABLE `pt_products` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(535) NOT NULL,
  `price` int(11) NOT NULL,
  `color` varchar(535) NOT NULL DEFAULT '',
  `rating` int(11) NOT NULL,
  `img_name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pt_sliders`
--

CREATE TABLE `pt_sliders` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(535) NOT NULL,
  `img_name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pt_transactions`
--

CREATE TABLE `pt_transactions` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `color` varchar(535) NOT NULL DEFAULT '',
  `qty` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `qty_price` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `delivered` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pt_admin`
--
ALTER TABLE `pt_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pt_categories`
--
ALTER TABLE `pt_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pt_contact`
--
ALTER TABLE `pt_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pt_products`
--
ALTER TABLE `pt_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pt_sliders`
--
ALTER TABLE `pt_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pt_transactions`
--
ALTER TABLE `pt_transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pt_admin`
--
ALTER TABLE `pt_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pt_categories`
--
ALTER TABLE `pt_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pt_contact`
--
ALTER TABLE `pt_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pt_products`
--
ALTER TABLE `pt_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pt_sliders`
--
ALTER TABLE `pt_sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pt_transactions`
--
ALTER TABLE `pt_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
