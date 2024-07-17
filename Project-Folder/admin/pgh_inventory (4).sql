-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2024 at 07:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pgh_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `itemID` int(11) NOT NULL,
  `itemName` varchar(50) NOT NULL,
  `unitOfMeasure` varchar(25) NOT NULL,
  `itemType` varchar(25) NOT NULL,
  `quantity` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `minStockLevel` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `itemStatus` enum('selling','not selling') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemID`, `itemName`, `unitOfMeasure`, `itemType`, `quantity`, `minStockLevel`, `itemStatus`) VALUES
(1, 'Test1', 'piece', 'Alcohol', 7, 10, 'selling'),
(2, 'Test2', 'kg', 'Ingredient', 8, 10, 'selling'),
(3, 'Test3', 'piece', 'Soda', 5, 10, 'selling'),
(4, 'Test4', 'piece', 'Drink', 3, 70, 'selling');

-- --------------------------------------------------------

--
-- Table structure for table `item_changes`
--

CREATE TABLE `item_changes` (
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `itemID` int(11) NOT NULL,
  `comments` varchar(500) NOT NULL,
  `adjustedQuantity` int(11) DEFAULT NULL,
  `quantityAfter` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_costs`
--

CREATE TABLE `item_costs` (
  `itemID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `cost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_costs`
--

INSERT INTO `item_costs` (`itemID`, `supplierID`, `cost`) VALUES
(1, 1, 2000),
(1, 2, 2234),
(2, 1, 1234);

-- --------------------------------------------------------

--
-- Table structure for table `po_item`
--

CREATE TABLE `po_item` (
  `POID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `orderedQuantity` int(11) NOT NULL,
  `fulfilledQuantity` int(11) NOT NULL,
  `itemOrderStatus` enum('unfulfilled','partial','fulfilled','') NOT NULL,
  `orderCostSum` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pr_item`
--

CREATE TABLE `pr_item` (
  `PRID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `requestQuantity` int(11) DEFAULT 1,
  `estimatedCost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `POID` int(11) NOT NULL,
  `PRID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `PODateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `POStatus` enum('Open','Closed') NOT NULL DEFAULT 'Open',
  `dueDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `PRID` int(11) NOT NULL,
  `requestedBy` int(11) NOT NULL,
  `PRDateRequested` date NOT NULL,
  `dateNeeded` date NOT NULL,
  `PRStatus` enum('pending','approved','ordered') NOT NULL DEFAULT 'pending',
  `estimatedCost` int(11) DEFAULT NULL,
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`PRID`, `requestedBy`, `PRDateRequested`, `dateNeeded`, `PRStatus`, `estimatedCost`, `reason`) VALUES
(1, 1, '2024-07-10', '2024-07-22', 'pending', 32121313, 'wadawd');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplierID` int(11) NOT NULL,
  `companyName` varchar(25) NOT NULL,
  `address` varchar(25) NOT NULL,
  `contactNum` varchar(25) DEFAULT NULL,
  `supplierEmail` varchar(25) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplierID`, `companyName`, `address`, `contactNum`, `supplierEmail`, `status`) VALUES
(1, 'GOGO', 'asd', '123', NULL, 'active'),
(2, 'dfhsdfg', 'sdfgsdfg', '23452354', 'sdf@gmail.com', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `department` varchar(25) NOT NULL,
  `permissions` varchar(25) NOT NULL,
  `password` varchar(300) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `workStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `fname`, `lname`, `department`, `permissions`, `password`, `email`, `created_at`, `workStatus`) VALUES
(1, 'Hamsam', 'Jams', 'Cook', 'asd', '$2y$10$9ZF8QRwnnQQh4T/PJMlDUeEwtNThqt5HTGKJc9eVF/.AaImOZFvB2', 'ham@gmail.com', '2024-07-10 01:51:54', 1),
(3, 'password', 'test', 'wqwe', 'qwe', '$2y$10$1IzGwYkTRV.5jpfkZnvYR.oh4I2J9Y1h/qQJgjQpEVsjsUIyu.BVi', 'password@gmail.com', '2024-07-10 01:59:54', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `item_changes`
--
ALTER TABLE `item_changes`
  ADD PRIMARY KEY (`dateModified`),
  ADD KEY `Item` (`itemID`);

--
-- Indexes for table `item_costs`
--
ALTER TABLE `item_costs`
  ADD UNIQUE KEY `cost` (`itemID`,`supplierID`) USING BTREE,
  ADD KEY `item_costs_ibfk_2` (`supplierID`);

--
-- Indexes for table `po_item`
--
ALTER TABLE `po_item`
  ADD UNIQUE KEY `POID` (`POID`,`itemID`,`supplierID`),
  ADD KEY `supplierID` (`supplierID`),
  ADD KEY `itemID` (`itemID`);

--
-- Indexes for table `pr_item`
--
ALTER TABLE `pr_item`
  ADD UNIQUE KEY `PRID` (`PRID`,`itemID`,`supplierID`) USING BTREE,
  ADD KEY `supplierID` (`supplierID`),
  ADD KEY `itemID` (`itemID`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`POID`),
  ADD KEY `prid` (`PRID`),
  ADD KEY `suppid` (`supplierID`) USING BTREE;

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`PRID`),
  ADD KEY `userid` (`requestedBy`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `POID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `PRID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_costs`
--
ALTER TABLE `item_costs`
  ADD CONSTRAINT `item_costs_ibfk_1` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `item_costs_ibfk_2` FOREIGN KEY (`supplierID`) REFERENCES `supplier` (`supplierID`) ON UPDATE CASCADE;

--
-- Constraints for table `po_item`
--
ALTER TABLE `po_item`
  ADD CONSTRAINT `po_item_ibfk_1` FOREIGN KEY (`supplierID`) REFERENCES `supplier` (`supplierID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `po_item_ibfk_3` FOREIGN KEY (`supplierID`) REFERENCES `item_costs` (`supplierID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `po_item_ibfk_4` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `po_item_ibfk_5` FOREIGN KEY (`POID`) REFERENCES `purchase_order` (`POID`) ON UPDATE CASCADE;

--
-- Constraints for table `pr_item`
--
ALTER TABLE `pr_item`
  ADD CONSTRAINT `pr_item_ibfk_2` FOREIGN KEY (`PRID`) REFERENCES `purchase_requests` (`PRID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pr_item_ibfk_3` FOREIGN KEY (`supplierID`) REFERENCES `supplier` (`supplierID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pr_item_ibfk_4` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `purchase_order_ibfk_1` FOREIGN KEY (`PRID`) REFERENCES `purchase_requests` (`PRID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_ibfk_2` FOREIGN KEY (`supplierID`) REFERENCES `supplier` (`supplierID`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD CONSTRAINT `purchase_requests_ibfk_1` FOREIGN KEY (`requestedBy`) REFERENCES `users` (`userID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
