-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2024 at 06:20 AM
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
-- Database: `cainventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apple_inventory`
--

CREATE TABLE `tbl_apple_inventory` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apple_inventory`
--

INSERT INTO `tbl_apple_inventory` (`id`, `services`, `property_tag_name`, `description`, `property_tag`, `date_acquired`, `actual_user`, `remarks`, `specify`, `service_unserviceable`) VALUES
(1, 'HR', 'Melanie Marcaden', 'Macbook Pro', 'HR-129293', '09/04/2024', 'Melanie Marcaden', '', '', 'Serviceable');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apple_inventory_archive`
--

CREATE TABLE `tbl_apple_inventory_archive` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory`
--

CREATE TABLE `tbl_inventory` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inventory`
--

INSERT INTO `tbl_inventory` (`id`, `services`, `property_tag_name`, `description`, `property_tag`, `date_acquired`, `actual_user`, `remarks`, `specify`, `service_unserviceable`) VALUES
(1, 'Accounting', 'Nida Camback', 'Dell Optilplex 500', 'As-123-45', '01/02/2017', 'Nida Camback', '', '', 'Serviceable'),
(2, 'Accounting', 'Bea Binene', 'Hp Tower Desktop', 'As-1234-567', '04/07/2015', 'Bea Binene', '', '', 'Serviceable'),
(3, 'Faculty', 'Jah Moral', 'Dell OPtiplex 700', 'Fac-12345', '09/04/2018', 'Jah Moral', '', '', 'Serviceable'),
(4, 'Laboratory', 'Johnson Campante', 'Asus MTX-123', 'Lab-1235', '09/02/2024', 'Johnson Campante', '', '', 'Serviceable');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory_archive`
--

CREATE TABLE `tbl_inventory_archive` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_laptop_inventory`
--

CREATE TABLE `tbl_laptop_inventory` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_laptop_inventory`
--

INSERT INTO `tbl_laptop_inventory` (`id`, `services`, `property_tag_name`, `description`, `property_tag`, `date_acquired`, `actual_user`, `remarks`, `specify`, `service_unserviceable`) VALUES
(1, 'Faculty', 'Enrico Far', 'Lenove Laptop', 'Fac-4569', '09/14/2022', 'Enrico Far', '', '', 'Serviceable'),
(2, 'Accounting', 'Karen Kapepe', 'Dell Opmtizer', 'As-102093', '09/23/2015', 'Karen Kapepe', '', '', 'Serviceable');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_laptop_inventory_archive`
--

CREATE TABLE `tbl_laptop_inventory_archive` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_m365_acc`
--

CREATE TABLE `tbl_m365_acc` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `temporary_pass` varchar(255) DEFAULT NULL,
  `permanent_pass` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_m365_acc`
--

INSERT INTO `tbl_m365_acc` (`id`, `username`, `account_name`, `display_name`, `actual_user`, `temporary_pass`, `permanent_pass`, `remarks`, `status`) VALUES
(1, 'hr@azhg.onmicrosoft.com', 'HR Department', 'Hr Department', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_m365_acc_archive`
--

CREATE TABLE `tbl_m365_acc_archive` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `temporary_pass` varchar(255) DEFAULT NULL,
  `permanent_pass` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scanner_inventory`
--

CREATE TABLE `tbl_scanner_inventory` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_scanner_inventory`
--

INSERT INTO `tbl_scanner_inventory` (`id`, `services`, `property_tag_name`, `description`, `property_tag`, `date_acquired`, `actual_user`, `remarks`, `specify`, `service_unserviceable`) VALUES
(1, 'OPD', 'Albert Berto', 'Scanner Duplo', 'OPD-1229', '09/06/2022', 'Albert Berto', '', '', 'Unserviceable');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scanner_inventory_archive`
--

CREATE TABLE `tbl_scanner_inventory_archive` (
  `id` int(11) NOT NULL,
  `services` varchar(255) DEFAULT NULL,
  `property_tag_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `property_tag` varchar(255) DEFAULT NULL,
  `date_acquired` varchar(255) DEFAULT NULL,
  `actual_user` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `specify` varchar(255) DEFAULT NULL,
  `service_unserviceable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `id_services` int(11) NOT NULL,
  `services_category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`id_services`, `services_category`) VALUES
(1, 'Laboratory'),
(2, 'HR'),
(3, 'Accounting'),
(4, 'OPD'),
(5, 'X-Ray'),
(6, 'Faculty');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `account_type` varchar(255) DEFAULT NULL,
  `img` longtext DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `last_name`, `first_name`, `middle_name`, `contact`, `email`, `username`, `password`, `account_type`, `img`, `status`) VALUES
(1, 'Gamasan', 'Jeff Ronald', 'Gaston', '09452869822', 'jeffgamasan@gmail.com', 'zukululu', '$2y$10$Cg2J8o9FoLlPa/brj1PM1OII37B.Xuyg8JuKe5cF6YO5G7areHV7C', 'admin', '66d7dce7ebdda_WIN_20240119_11_43_15_Pro.jpg', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_apple_inventory`
--
ALTER TABLE `tbl_apple_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_apple_inventory_archive`
--
ALTER TABLE `tbl_apple_inventory_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_inventory_archive`
--
ALTER TABLE `tbl_inventory_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_laptop_inventory`
--
ALTER TABLE `tbl_laptop_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_laptop_inventory_archive`
--
ALTER TABLE `tbl_laptop_inventory_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_m365_acc`
--
ALTER TABLE `tbl_m365_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_m365_acc_archive`
--
ALTER TABLE `tbl_m365_acc_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_scanner_inventory`
--
ALTER TABLE `tbl_scanner_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_scanner_inventory_archive`
--
ALTER TABLE `tbl_scanner_inventory_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_services`
--
ALTER TABLE `tbl_services`
  ADD PRIMARY KEY (`id_services`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_apple_inventory`
--
ALTER TABLE `tbl_apple_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_apple_inventory_archive`
--
ALTER TABLE `tbl_apple_inventory_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_inventory_archive`
--
ALTER TABLE `tbl_inventory_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_laptop_inventory`
--
ALTER TABLE `tbl_laptop_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_laptop_inventory_archive`
--
ALTER TABLE `tbl_laptop_inventory_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_m365_acc`
--
ALTER TABLE `tbl_m365_acc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_m365_acc_archive`
--
ALTER TABLE `tbl_m365_acc_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_scanner_inventory`
--
ALTER TABLE `tbl_scanner_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_scanner_inventory_archive`
--
ALTER TABLE `tbl_scanner_inventory_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `id_services` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
