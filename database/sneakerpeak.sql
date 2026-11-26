-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2023 at 07:07 PM
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
-- Database: `sneakerpeak`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Admin_ContactNumber` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(60) NOT NULL DEFAULT 'noprofil.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `Admin_ContactNumber`, `email`, `password`, `image`) VALUES
(1, 'TAN YAN TING', '0127595095', 'ting79854@gmail.com', 'Ting5201314*', 'TAN YAN TING - 2023.06.05 - 04.30.05pm.jpg'),
(32, 'Wong Jun Loong', '01159489156', '1211204748@student.mmu.edu.my', 'Ting5201314*', 'noprofil.jpg'),
(34, 'YUJIE0405', '01116165100', '1211202710@student.mmu.edu.my', 'Likexincan123!', 'YUJIE0405 - 2023.06.10 - 10.40.39am.png'),
(35, 'Lim Zi Chien', '0123456789', '1211204161@student.mmu.edu.my', 'Chien814!', 'noprofil.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `Brand_ID` int(255) NOT NULL,
  `Brand_Name` varchar(255) NOT NULL,
  `Brand_logo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`Brand_ID`, `Brand_Name`, `Brand_logo`) VALUES
(13, 'Nike', 'nike_PNG5.png'),
(14, 'Adidas', 'Adidas-Logo-1991.jpg'),
(15, 'Puma', 'puma.png'),
(16, 'Converse', 'Converse_(shoe_company)-Logo.wine.png'),
(19, 'Vans', 'Vans-Logo-1966.png');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `ID` int(255) NOT NULL,
  `UID` varchar(255) NOT NULL,
  `Prod_Img1` varchar(255) NOT NULL,
  `Product_Name` varchar(255) NOT NULL,
  `Prod_Price` varchar(255) NOT NULL,
  `Select_Size` varchar(255) NOT NULL,
  `Select_qty` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Customer_Address` varchar(255) NOT NULL,
  `Customer_Address_2` varchar(255) NOT NULL,
  `Customer_ContractNumber` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `code` mediumint(50) NOT NULL,
  `status` text NOT NULL,
  `image` varchar(60) NOT NULL DEFAULT 'noprofil.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `Customer_Address`, `Customer_Address_2`, `Customer_ContractNumber`, `email`, `password`, `code`, `status`, `image`) VALUES
(53, 'TAN CHONG MENG', '26, Jalan Jaya Mas 3, 81300, Skudai, Johor', '', '01159489156', '1211204752@student.mmu.edu.my', 'Ting5201314*', 0, 'verified', 'noprofil.jpg'),
(91, 'YUJIE0405', '28,JalanEh36,Taman Evergreen Heigh,83000,BatuPahat,Johor.', '31,Jalan Taman Soga,Taman Soga,83000,BatuPahat,Johor', '01116165100', 'xingmingzustar@gmail.com', 'Yujie45!', 0, 'verified', 'noprofil.jpg'),
(99, 'TEE YI JIE', '28,Jalan EH 36', '', '01116165100', 'yijietee123@gmail.com', 'Yujie0405!', 696008, 'notverified', 'noprofil.jpg'),
(100, 'Tan Yan Ting', '28,Jalan EH 36', '', '01116165100', 'benb50596@gmail.com', 'Kang5201314*', 0, 'verified', 'noprofil.jpg'),
(105, 'TEE YI JIE', '28,Jalan EH 36', '', '0111616510', 'yjt74940@gmail.com', 'Likexincan123!', 0, 'verified', 'noprofil.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `oder`
--

CREATE TABLE `oder` (
  `ID` int(255) NOT NULL,
  `UID` varchar(255) NOT NULL,
  `Order_code` varchar(255) NOT NULL,
  `Order_Date` varchar(255) NOT NULL,
  `Order_TotalAmmount` varchar(255) NOT NULL,
  `ShippingFeed` varchar(255) NOT NULL,
  `Payment_Type` varchar(255) NOT NULL,
  `Payment_Status` varchar(255) NOT NULL,
  `Order_Status` varchar(255) NOT NULL,
  `Promote_Code` varchar(255) NOT NULL,
  `ask` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oder`
--

INSERT INTO `oder` (`ID`, `UID`, `Order_code`, `Order_Date`, `Order_TotalAmmount`, `ShippingFeed`, `Payment_Type`, `Payment_Status`, `Order_Status`, `Promote_Code`, `ask`) VALUES
(26, '88', '320286', '05/06/2023', ' 369 ', ' 20.00', 'Card_Payment', 'Complete', 'Shipped', '727165', '0'),
(30, '91', '286451', '06/06/2023', ' 1058 ', ' 0.00', 'Card_Payment', 'Complete', 'Shipped', '929551', '0'),
(31, '91', '464835', '06/06/2023', ' 389 ', ' 20.00', 'Card_Payment', 'Complete', 'Shipped', '', '0'),
(32, '91', '250774', '08/06/2023', ' 1617 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '1'),
(33, '91', '194754', '09/06/2023', ' 239 ', ' 20.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(34, '91', '805804', '06/06/2023', ' 539 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(35, '91', '989830', '06/06/2023', ' 239 ', ' 20.00', 'QR_Payment', 'Complete', 'Pending', '', '0'),
(36, '91', '281643', '06/06/2023', ' 389 ', ' 20.00', 'Card_Payment', 'Paying', 'Pending', '', '0'),
(37, '91', '297004', '06/06/2023', ' 389 ', ' 20.00', 'Card_Payment', 'Complete', 'Shipped', '', '0'),
(38, '91', '674892', '06/06/2023', ' 459 ', ' 20.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(39, '91', '417104', '07/06/2023', ' 669 ', ' 0.00', 'Card_Payment', 'Complete', 'Complete', '', '0'),
(40, '91', '957372', '07/06/2023', ' 239 ', ' 20.00', 'QR_Payment', 'Complete', 'Pending', '', '0'),
(41, '91', '436296', '07/06/2023', ' 669 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(42, '91', '832005', '07/06/2023', ' 1107 ', ' 0.00', 'QR_Payment', 'Complete', 'Complete', '', '0'),
(43, '91', '327675', '07/06/2023', ' 389 ', ' 20.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(44, '91', '999044', '15/06/2023', ' 2952 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(45, '91', '755224', '15/06/2023', ' 389 ', ' 20.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(46, '94', '382772', '15/06/2023', ' 1711.8 ', ' 0.00', 'QR_Payment', 'Complete', 'Complete', '433932', '0'),
(47, '91', '579631', '15/06/2023', ' 389 ', ' 20.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(48, '91', '420085', '15/06/2023', ' 669 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(49, '91', '291105', '15/06/2023', ' 239 ', ' 20.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(50, '91', '565217', '25/06/2023', ' 2396.25 ', ' 0.00', 'Card_Payment', 'Complete', 'Complete', '500216', '0'),
(51, '91', '332728', '25/06/2023', ' 669 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(52, '91', '635014', '25/06/2023', ' 669 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(53, '91', '497342', '25/06/2023', ' 639 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(54, '91', '482119', '25/06/2023', ' 1149 ', ' 0.00', 'Card_Payment', 'Complete', 'Pending', '', '0'),
(55, '91', '428825', '25/06/2023', ' 639 ', ' 0.00', 'unset', 'Paying', 'Pending', '967772', '0'),
(56, '91', '129299', '25/06/2023', ' 669 ', ' 0.00', 'unset', 'Paying', 'Pending', '', '0'),
(57, '91', '387752', '25/06/2023', ' 669 ', ' 0.00', 'unset', 'Paying', 'Pending', '', '0'),
(58, '91', '662738', '25/06/2023', ' 635.55 ', ' 0.00', 'unset', 'Paying', 'Pending', '183318', '0'),
(59, '101', '551802', '25/06/2023', ' 2778.75 ', ' 0.00', 'Card_Payment', 'Complete', 'Complete', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `oder_address`
--

CREATE TABLE `oder_address` (
  `ID` int(255) NOT NULL,
  `UID` varchar(255) NOT NULL,
  `Order_code` varchar(255) NOT NULL,
  `Order_Address` varchar(255) NOT NULL,
  `Receiver_Name` varchar(255) NOT NULL,
  `Receiver_Contract` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oder_address`
--

INSERT INTO `oder_address` (`ID`, `UID`, `Order_code`, `Order_Address`, `Receiver_Name`, `Receiver_Contract`) VALUES
(1, '', '788629', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(2, '', '374035', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(3, '', '847135', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(4, '', '910518', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(5, '88', '627733', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(6, '88', '362371', ' srrsd5e5e, ghjggy, 83000, batu pahat, johor', 'yijiw', '01116165100'),
(7, '88', '192298', ' srrsd5e5e, ghjggy, 83000, batu pahat, johor', 'yijiw', '01116165100'),
(8, '88', '192298', ' srrsd5e5e, ghjggy, 83000, batu pahat, johor', 'yijiw', '01116165100'),
(9, '88', '579691', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(10, '88', '579691', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(11, '88', '475070', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(12, '88', '475070', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(13, '88', '313692', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(14, '88', '321283', ' srrsd5e5e, ghjggy, 83000, batu pahat, johor', 'yijiw', '01116165100'),
(15, '88', '682983', ' srrsd5e5e, ghjggy, 83000, batu pahat, johor', 'yijiw', '01116165100'),
(16, '88', '556417', ' srrsd5e5e, ghjggy, 83000, batu pahat, johor', 'yijiw', '01116165100'),
(17, '88', '838218', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(18, '88', '909212', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(19, '88', '320286', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(20, '91', '286451', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(21, '91', '464835', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(22, '91', '250774', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(23, '91', '194754', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(24, '91', '805804', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(25, '91', '989830', ' 28,JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor', 'yijie', '01116165100'),
(26, '91', '989830', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(27, '91', '989830', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(28, '91', '281643', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', ' xa', ' asxas'),
(29, '91', '297004', ' 28,JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(30, '91', '674892', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(31, '91', '417104', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(32, '91', '957372', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(33, '91', '436296', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(34, '91', '832005', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(35, '91', '832005', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijie', '01116165100'),
(36, '91', '327675', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijiw', '01116165100'),
(37, '91', '999044', ' 28,JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'Tee Yi Jie', '01116165100'),
(38, '91', '755224', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijiw', '01116165100'),
(39, '91', '755224', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'yijiw', '999999999999999999999999999999999999999999999999999'),
(40, '91', '755224', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'Tee Yi Jie', '01116165100'),
(41, '94', '382772', ' 27,Jalan EH 36, Taman Indah, 82000, Kluang, Johor', 'Tee Yi Jie', '01116165100'),
(42, '94', '382772', ' 27,Jalan EH 36, Taman Indah, 82000, Kluang, Johor', 'Tee Yi Jie', '01116165100'),
(43, '94', '382772', ' 27,Jalan EH 36, Taman Indah, 82000, Kluang, Johor', 'Tee Yi Jie', '01116165100'),
(44, '94', '382772', ' 27,Jalan EH 36, Taman Indah, 82000, Kluang, Johor', 'Tee Yi Jie', '01116165100'),
(45, '91', '579631', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor', 'yijie', '01116165100'),
(46, '91', '420085', ' JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'Tee Yi Jie', '01116165100'),
(47, '91', '291105', ' 28,JalanEh36, Taman Evergreen Heigh, 83000, BatuPahat, Johor.', 'Tee Yi Jie', '01116165100'),
(48, '91', '565217', ' Jalan Taman Soga, Taman Soga, 83000, BatuPahat, Johor', 'yujie', '01135236466'),
(49, '91', '565217', ' Jalan Taman Soga, Taman Soga, 83000, BatuPahat, Johor', 'yujie', '01135236466'),
(50, '91', '332728', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(51, '91', '635014', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(52, '91', '497342', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(53, '91', '482119', ' 28,Jalan EH 36, TAMAN EVERGREEN HEIGHt, 83000, Batu Pahat, Johor', 'yijie', '01116165100'),
(54, '101', '551802', ' Jalan EH 36, Taman Evergreen Height, 83000, BatuPahat, Johor', 'oop', '0123456789'),
(55, '101', '551802', ' Jalan EH 36, Taman Evergreen Height, 83000, BatuPahat, Johor', 'oop', '0123456789'),
(56, '101', '551802', ' Jalan EH 36, Taman Evergreen Height, 83000, BatuPahat, Johor', 'oop', '0123456789');

-- --------------------------------------------------------

--
-- Table structure for table `oder_details`
--

CREATE TABLE `oder_details` (
  `ID` int(255) NOT NULL,
  `UID` varchar(255) NOT NULL,
  `Order_code` varchar(255) NOT NULL,
  `Order_Product` varchar(255) NOT NULL,
  `Order_ProdcutP` varchar(255) NOT NULL,
  `Order_Size` varchar(255) NOT NULL,
  `Order_qty` varchar(255) NOT NULL,
  `Order_Day` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oder_details`
--

INSERT INTO `oder_details` (`ID`, `UID`, `Order_code`, `Order_Product`, `Order_ProdcutP`, `Order_Size`, `Order_qty`, `Order_Day`) VALUES
(1, '', '788629', 'KD Trey 5 X EP W', '369', '7', '2', '23/05/2023'),
(2, '', '788629', 'KD TREY 5 X EP 101', '369', '8', '1', '23/05/2023'),
(3, '', '318970', 'KD15 EP 100', '669.00', '8.5', '1', '23/05/2023'),
(4, '', '312244', 'KD15 EP 100', '669.00', '8.5', '1', '23/05/2023'),
(5, '', '797376', 'KD15 EP 100', '669.00', '8.5', '1', '23/05/2023'),
(6, '', '374035', 'KD15 EP 100', '669.00', '8.5', '1', '23/05/2023'),
(7, '', '723167', 'KD15 EP 100', '669.00', '8.5', '1', '23/05/2023'),
(8, '', '847135', 'KD15 EP 100', '669.00', '8.5', '1', '23/05/2023'),
(9, '', '910518', 'KD15 EP 100', '669.00', '7.5', '1', '24/05/2023'),
(10, '88', '627733', 'KD15 NRG EP', '669.00', '8.5', '2', '24/05/2023'),
(11, '88', '575700', 'KD15 EP 100', '669.00', '8', '3', '24/05/2023'),
(12, '88', '892525', 'KD15 EP 100', '669.00', '8', '3', '24/05/2023'),
(13, '88', '785616', 'KD15 EP 100', '669.00', '8', '32', '24/05/2023'),
(14, '88', '643994', 'KD15 EP 100', '669.00', '8', '32', '24/05/2023'),
(15, '88', '362371', 'KD15 EP 100', '669.00', '8', '1', '24/05/2023'),
(16, '88', '362371', 'Nike Pegasus 40 Premium W', '639.00', '9', '1', '24/05/2023'),
(17, '88', '192298', 'Nike Pegasus 40 Premium W', '639.00', '10', '2', '25/05/2023'),
(18, '88', '192298', 'Nike Pegasus 40 Premium W', '639.00', '8.5', '1', '25/05/2023'),
(19, '88', '579691', 'KD Trey 5 X EP W', '369', '8', '1', '31/05/2023'),
(20, '88', '579691', 'Nike Zegama R', '639.00', '10', '1', '31/05/2023'),
(21, '88', '579691', 'HARDEN VOL. 7', '629.00', '10.5', '1', '31/05/2023'),
(22, '88', '475070', 'KD Trey 5 X EP W', '369', '9.5', '2', '31/05/2023'),
(23, '88', '313692', 'KD Trey 5 X EP W', '369', '9.5', '2', '31/05/2023'),
(24, '88', '313692', 'KD TREY 5 X EP 101', '369', '8.5', '1', '31/05/2023'),
(25, '88', '321283', 'KD Trey 5 X EP W', '369.00', '9.5', '1', '01/06/2023'),
(26, '88', '321283', 'KD15 EP 100', '669.00', '10.5', '1', '01/06/2023'),
(27, '88', '321283', 'Nike Pegasus 40 Premium W', '639.00', '10.5', '1', '01/06/2023'),
(28, '88', '682983', 'KD TREY 5 X EP 101', '369', '9', '1', '01/06/2023'),
(29, '88', '556417', 'KD TREY 5 X EP 101', '369', '8.5', '1', '01/06/2023'),
(30, '88', '838218', 'Tatum 1 W', '539', '6', '1', '05/06/2023'),
(31, '88', '909212', 'KD TREY 5 X EP 101', '369', '8.5', '1', '05/06/2023'),
(32, '88', '320286', 'KD TREY 5 X EP 101', '369', '8.5', '1', '05/06/2023'),
(33, '88', '268949', 'KD TREY 5 X EP 101', '369', '8.5', '1', '05/06/2023'),
(34, '88', '182053', 'KD TREY 5 X EP 101', '369', '8.5', '1', '05/06/2023'),
(35, '91', '458029', 'Tatum 1 W', '539', '6.5', '2', '06/06/2023'),
(36, '91', '286451', 'Tatum 1 W', '539', '6.5', '2', '06/06/2023'),
(37, '91', '464835', 'KD Trey 5 X EP W', '369.00', '8', '1', '06/06/2023'),
(38, '91', '250774', 'Tatum 1 W', '539', '7', '3', '06/06/2023'),
(39, '91', '194754', 'Nike Phantom GT2 Academy IC', '219.00', '9', '1', '06/06/2023'),
(40, '91', '805804', 'Tatum 1 W', '539', '7.5', '1', '06/06/2023'),
(41, '91', '989830', 'Nike Phantom GT2 Academy IC', '219.00', '9', '1', '06/06/2023'),
(42, '91', '281643', 'KD TREY 5 X EP 101', '369', '9', '1', '06/06/2023'),
(43, '91', '297004', 'KD TREY 5 X EP 101', '369', '9', '1', '06/06/2023'),
(44, '91', '674892', 'AIR FORCE 1 07W', '439.00', '7', '1', '06/06/2023'),
(45, '91', '417104', 'KD15 NRG EP', '669.00', '9', '1', '07/06/2023'),
(46, '91', '957372', 'Nike Phantom GT2 Academy IC', '219.00', '9', '1', '07/06/2023'),
(47, '91', '436296', 'KD15 NRG EP', '669.00', '8.5', '1', '07/06/2023'),
(48, '91', '832005', 'KD Trey 5 X EP W', '369', '7', '3', '07/06/2023'),
(49, '91', '327675', 'KD Trey 5 X EP W9', '369', '7', '1', '07/06/2023'),
(50, '91', '999044', 'KD Trey 5 X EP W', '369', '7', '8', '15/06/2023'),
(51, '91', '755224', 'KD Trey 5 X EP W', '369', '7.5', '1', '15/06/2023'),
(52, '94', '382772', 'KD Trey 5 X EP W', '369', '7', '6', '15/06/2023'),
(53, '94', '382772', 'Nike Zegama R', '639.00', '9', '1', '15/06/2023'),
(54, '91', '579631', 'PUREBOOST 22 SHOES', '369.00', '3.5', '1', '15/06/2023'),
(55, '91', '420085', 'KD15 NRG EP', '669.00', '8', '1', '15/06/2023'),
(56, '91', '291105', 'Nike Phantom GT2 Academy IC', '219.00', '8.5', '1', '15/06/2023'),
(57, '91', '565217', 'Nike Zegama R', '639.00', '8', '4', '25/06/2023'),
(58, '91', '565217', 'Nike Zegama B', '639.00', '8', '1', '25/06/2023'),
(59, '91', '332728', 'KD15 EP 100', '669.00', '7.5', '1', '25/06/2023'),
(60, '91', '635014', 'KD15 NRG EP', '669.00', '9', '1', '25/06/2023'),
(61, '91', '497342', 'Nike Zegama B', '639.00', '9', '1', '25/06/2023'),
(62, '91', '482119', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG', '1149.00', '6', '1', '25/06/2023'),
(63, '91', '428825', 'KD15 EP 100', '669.00', '9.5', '1', '25/06/2023'),
(64, '91', '129299', 'KD15 EP 100', '669.00', '9.5', '1', '25/06/2023'),
(65, '91', '387752', 'KD15 EP 100', '669.00', '9.5', '1', '25/06/2023'),
(66, '91', '662738', 'KD15 EP 100', '669.00', '9.5', '1', '25/06/2023'),
(67, '101', '551802', 'Nike Zegama B', '639.00', '9', '4', '25/06/2023'),
(68, '101', '551802', 'Nike Rival Fly 3B', '369.00', '7', '1', '25/06/2023');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Prod_ID` int(255) NOT NULL,
  `Prod_Name` varchar(255) NOT NULL,
  `Prod_Brand` varchar(255) NOT NULL,
  `Prod_Price` varchar(255) NOT NULL,
  `Prod_Colour` varchar(255) NOT NULL,
  `Prod_Cetegory` varchar(255) NOT NULL,
  `Prod_SubC` varchar(255) NOT NULL,
  `Prod_Details` varchar(255) NOT NULL,
  `Prod_img1` text NOT NULL,
  `Prod_img2` text NOT NULL,
  `Prod_img3` text NOT NULL,
  `Prod_Status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Prod_ID`, `Prod_Name`, `Prod_Brand`, `Prod_Price`, `Prod_Colour`, `Prod_Cetegory`, `Prod_SubC`, `Prod_Details`, `Prod_img1`, `Prod_img2`, `Prod_img3`, `Prod_Status`) VALUES
(102, 'KD Trey 5 X EP W', 'Nike', '369', 'Phantom/Green Strike/Light Orewood Brown/Earth', 'Male', ' Basketball', 'With its lightweight upper and plush support system, the KD Trey 5 X can help you float like KD, waiting for the perfect moment to drive to the hoop. A secure midfoot strap is suited for scoring binges and defensive stands, so that you can lock in and kee', 'KD 1.2.jpg', 'KD 1.3.jpg', 'KD 1.jpg', 'available'),
(103, 'Tatum 1 W', 'Nike', '539', 'White/University Blue/University Gold/University Red', 'Male', ' Basketball', 'So deep that we made a special-edition colourway to celebrate Jays hometown. The bold combo of red and white will make anyone from The Lou do a double-take. Fleur-de-lis designs on the heel and insole nod to the citys flag. And Archer Avenue? Well, thats ', 'Tatum 1.2.webp', 'Tatum 1.3.webp', 'Tatum 1.webp', 'Deleted'),
(104, 'KD TREY 5 X EP 101', 'Nike', '369', 'black/grey', 'Male', ' Basketball', 'With its lightweight upper and plush support system, the KD Trey 5 X can help you float like KD, waiting for the perfect moment to drive to the hoop. A secure midfoot strap is suited for scoring binges and defensive stands, so that you can lock in and kee', 'KD TREY 5 X EP 101.jpg', 'KD TREY 5 X EP 1012.jpg', 'KD TREY 5 X EP 1013.jpg', 'Deleted'),
(105, 'KD15 EP 100', 'Nike', '669.00', 'green/white', 'Male', ' Basketball', 'Clutch plays and impossible shots are all in a days work for Kevin Durant. Give him the ball, and he makes the game look easy. The KD15 is stripped down to provide just what he wants: plenty of bounce, a secure fit and a broken-in feel. Fewer materials un', 'KD15 EP 100.png', 'KD15 EP 101.png', 'KD15 EP 102.png', 'available'),
(106, 'KD15 NRG EP', 'Nike', '669.00', 'blue/pink/yellow/black', 'Male', ' Basketball', 'The laces feed through floating wings along the sides. When you tighten the laces, the wings pull down to help hold your foot in place.\r\nSmooth, Springy Sensation\r\nSoft, full-length foam plus a visible Zoom Air Strobel unit provide a dual layer of cushion', 'KD15 NRG EP 500.png', 'KD15 NRG EP 501.png', 'KD15 NRG EP 502.png', 'available'),
(107, 'Nike Pegasus 40 Premium W', 'Nike', '639.00', 'White/Football Grey/Bright Mandarin/Multi-Colour', 'Male', ' Running', 'A springy ride for every run, the Pegs familiar, just-for-you feel returns to help you accomplish your goals. This version has the same responsiveness and neutral support you love but with improved comfort in those sensitive areas of your foot, like the a', 'Nike Pegasus 40 Premium W1.webp', 'Nike Pegasus 40 Premium W2.jpg', 'Nike Pegasus 40 Premium W3.webp', 'available'),
(108, 'Nike Zegama R', 'Nike', '639.00', 'Diffused Taupe/Dark Pony/Sand Drift/Picante Red', 'Male', ' Running', 'Navigate the up and downs of uncompromising terrain with the Nike Zegama. Developed with great grip and stability, it has you covered, so you can keep climbing and reach greater personal heights when the going gets grimy and gritty. Whether its a challeng', 'Nike Zegama 200.webp', 'Nike Zegama 201.webp', 'Nike Zegama 202.webp', 'available'),
(109, 'Nike Zegama G', 'Nike', '639.00', ' Spring Green/Olive Flak/Black/White', 'Male', ' Running', 'Navigate the up and downs of uncompromising terrain with the Nike Zegama. Developed with great grip and stability, it has you covered, so you can keep climbing and reach greater personal heights when the going gets grimy and gritty. Whether its a challeng', 'Nike Zegama G 100.webp', 'Nike Zegama G 101.webp', 'Nike Zegama G 102.webp', 'available'),
(110, 'Nike Zegama B', 'Nike', '639.00', 'Black/White', 'Male', ' Running', 'Navigate the up and downs of uncompromising terrain with the Nike Zegama. Developed with great grip and stability, it has you covered, so you can keep climbing and reach greater personal heights when the going gets grimy and gritty. Whether its a challeng', 'Nike Zegama B100.webp', 'Nike Zegama B101.webp', 'Nike Zegama B102.webp', 'available'),
(111, 'Nike Rival Fly 3B', 'Nike', '369.00', 'Hyper Royal/Black/White', 'Male', ' Running', 'When youre ready to ramp up the speed and go the distance, take off in a model thats built for training and racing. Our latest version includes foam cushioning thats softer than ever, and abrasion-resistant rubber for extra durability on rocky roads. With', 'Nike Rival Fly 3B1.webp', 'Nike Rival Fly 3B2.webp', 'Nike Rival Fly 3B3.webp', 'available'),
(112, 'Nike Phantom GX Club Dynamic Fit MG', 'Nike', '299.0', 'Baltic Blue/White/Laser Blue/Pink Blast', 'Male', ' Football', 'Whether youre starting out or just playing for fun, Club boots get you on the pitch without compromising on quality. With synthetic leather and asymmetric lacing, they help provide precision touch to help you bury goals in the back of the net or set up a ', 'Nike Phantom GX Club Dynamic Fit MG1.webp', 'Nike Phantom GX Club Dynamic Fit MG2.webp', 'Nike Phantom GX Club Dynamic Fit MG3.webp', 'available'),
(113, 'Nike Mercurial Superfly 9 Club MG W', 'Nike', '269.00', 'White/Pink Blast/Baltic Blue', 'Male', ' Football', 'Instantly tilt the pitch in the bold design of the light and low-to-the-ground Superfly 9 Club MG. Fast is in the Air.', 'Nike Mercurial Superfly 9 Club MG W1.webp', 'Nike Mercurial Superfly 9 Club MG W2.webp', 'Nike Mercurial Superfly 9 Club MG W3.webp', 'available'),
(114, 'Nike Mercurial Superfly 9 Club MG B', 'Nike', '269.00', 'Black/Summit White/Volt/Dark Smoke Grey', 'Male', ' Football', 'Instantly tilt the pitch in the bold design of the light and low-to-the-ground Superfly 9 Club MG. Fast is in the Air.', 'Nike Mercurial Superfly 9 Club MG B1.webp', 'Nike Mercurial Superfly 9 Club MG B2.webp', 'Nike Mercurial Superfly 9 Club MG B3.webp', 'available'),
(115, 'Nike Phantom GT2 Academy IC', 'Nike', '219.00', 'Metallic Copper/Metallic Copper', 'Male', ' Football', 'Celebrate the games biggest competition in a design ignited by the science of connectivity and gravity that the world stage inspires. Featuring a stunning futuristic look made for the globes greatest players, these shoes have an updated design and pattern', 'Nike Phantom GT2 Academy IC1.webp', 'Nike Phantom GT2 Academy IC2.webp', 'Nike Phantom GT2 Academy IC3.webp', 'available'),
(116, 'AIR FORCE 1 07W', 'Nike', '439.00', 'white', 'Male', ' Sneaker', 'The radiance lives on in the Nike Air Force 1 07, the b-ball OG that puts a fresh spin on what you know best: durably stitched overlays, clean finishes and the perfect amount of flash to make you shine.', 'AIR FORCE 1 07W1.png', 'AIR FORCE 1 07W2.png', 'AIR FORCE 1 07W3.png', 'available'),
(117, 'AIR FORCE 1 07B', 'Nike', '439.00', 'white/blue', 'Male', ' Sneaker', 'The radiance lives on in the Nike Air Force 1 07, the b-ball OG that puts a fresh spin on what you know best: durably stitched overlays, clean finishes and the perfect amount of flash to make you shine.\r\n', 'AIR FORCE 1 07B1.jpg', 'AIR FORCE 1 07B2.jpg', 'AIR FORCE 1 07B3.jpg', 'available'),
(118, 'AIR JORDAN 3 RETRO (GS)', 'Nike', '595.00', 'white/blue', 'Male', ' Sneaker', 'LIMITED PAIRS , Strictly ONE PAIR PER PERSON (ONE PAIR PER USER ACCOUNT, ONE PAIR PER SHIPPING ADDRESS & ONE PAIR PER CREDIT CARD/ DEBIT CARD). \r\n\r\nAll QS item are No return, refund or exchange upon successful payment of above-mentioned products AND E-Vou', 'AIR JORDAN 3 RETRO (GS)1.jpg', 'AIR JORDAN 3 RETRO (GS)2.jpg', 'AIR JORDAN 3 RETRO (GS)3.jpg', 'available'),
(119, 'HARDEN VOL. 6', 'Adidas', '629.00', 'yellow', 'Male', ' Basketball', 'Strike when your opponent least expects it. Drive to the rim, pull up from deep and leave defenders in your wake — just like James Harden. A Boost midsole provides the energy return you need, from the opening whistle to those gruelling last seconds of ove', 'HARDEN VOL. 61.png', 'HARDEN VOL. 62.png', 'HARDEN VOL. 63.png', 'available'),
(120, 'HARDEN VOL. 7', 'Adidas', '629.00', 'black/white', 'Male', ' Basketball', 'From his lethal stepback to his love of luxury fashion, theres no doubt that James Harden has style. With his signature shoes from adidas Basketball, it all comes into play. The upper borrows its bold look from puffer jackets, while the details support ex', 'HARDEN VOL. 71.jpg', 'HARDEN VOL. 72.jpg', 'HARDEN VOL. 73.jpg', 'available'),
(121, 'HARDEN VOL. 7b', 'Adidas', '629.00', 'black', 'Male', ' Basketball', 'From his lethal stepback to his love of luxury fashion, theres no doubt that James Harden has style. With his signature shoes from adidas Basketball, it all comes into play. The upper borrows its bold look from puffer jackets, while the details support ex', 'HARDEN VOL. 7b1.jpg', 'HARDEN VOL. 7b2.jpg', 'HARDEN VOL. 7b3.jpg', 'available'),
(122, 'Nike Air Deldon Hoodie', 'Nike', '319.00', 'Safety Orange/Citron Tint/Citron Tint', 'Female', ' Basketball', 'Elena Delle Donnes debut basketball shoe invites everyone to step in and take a shot at the game. Made for everyone, it has a collapsible heel and a large strap, so you can get in and secure it with one hand. Full-length Air cushioning and a forefoot Zoom', 'Nike Air Deldon Hoodie 1.webp', 'Nike Air Deldon Hoodie 2.webp', 'Nike Air Deldon Hoodie 3.webp', 'available'),
(123, 'Nike Cosmic Unity 2W', 'Nike', '379.00', 'Wolf Grey/Pure Platinum/Cone/Black', 'Female', ' Basketball', 'Celebrate the love and joy of the game with the Nike Cosmic Unity 2. Made from at least 20% recycled content by weight, it provides enhanced responsiveness and support. This shoe will help keep you fresh and secure without overloading it with extra grams,', 'Nike Cosmic Unity 21.jpg', 'Nike Cosmic Unity 22.webp', 'Nike Cosmic Unity 23.webp', 'available'),
(124, 'Air Jordan XXXVII Low', 'Nike', '879.00', 'Team Red/University Red/Muslin/Sail', 'Female', ' Basketball', 'A basketball shoe for you and your unique game. We gathered insights from female ballers and integrated them into this edition of the AJ XXXVII Low, engineered specifically for women. Focusing the design on speed and flexibility, we swapped in full-length', 'Air Jordan XXXVII Low1.webp', 'Air Jordan XXXVII Low2.webp', 'Air Jordan XXXVII Low3.webp', 'available'),
(125, ' Air Jordan 5 Retro', 'Nike', '869.00', 'Martian Sunrise/Fire Red/Muslin/Black', 'Female', ' Basketball', 'Get out-of-this-world with the latest edition of the AJ5. The beloved Tinker Hatfield design is back with a cosmic colourway that begs the question where will you take your game next? Made from premium suede, the Martian Sunrise upper adds otherworldly al', 'Air Jordan 5 Retro1.jpg', 'Air Jordan 5 Retro2.webp', 'Air Jordan 5 Retro3.jpg', 'available'),
(126, 'Nike x Ambush Air Adjust Force', 'Nike', '529.00', 'Light Madder Root/Madder Root/Habanero Red/Burgundy Crush', 'Female', ' Basketball', 'Yoon Ahn hits refresh on the clandestine hoops shoe you ve longed to see back on the streets. From luxurious nubuck leather to plastic details, the Ambush branded shoe bridges iconic b-ball style with runway flair. And of course, we ve kept the removable ', 'Nike x Ambush Air Adjust Force1.webp', 'Nike x Ambush Air Adjust Force2.webp', 'Nike x Ambush Air Adjust Force3.webp', 'available'),
(127, 'Nike Pegasus 40 SE', 'Nike', '589.00', 'Black/Green Abyss/Baltic Blue/Hyper Pink', 'Female', ' Running', 'Whether you re logging long marathon miles, squeezing in a speed session before the sun goes down or hopping into a spontaneous group jaunt, its still the established road runner you can put your faith in, day after day, run after run. Sharp, bright hues ', 'Nike Pegasus 40 SE1.webp', 'Nike Pegasus 40 SE2.jpg', 'Nike Pegasus 40 SE3.webp', 'available'),
(128, ' Nike Pegasus 40 Premium', 'Nike', '639.00', 'White/Photon Dust/University Red/Multi-Colour', 'Female', ' Running', 'hether you re logging long marathon miles, squeezing in a speed session before the sun goes down or hopping into a spontaneous group jaunt, its still the established road runner you can put your faith in, day after day, run after run. This design celebrat', 'Nike Pegasus 40 Premium2.webp', 'Nike Pegasus 40 Premium1.jpg', 'Nike Pegasus 40 Premium3.webp', 'available'),
(129, 'Nike Winflo 10 Premium', 'Nike', '489.00', 'Pearl Pink/Coral Chalk/White/Midnight Navy', 'Female', ' Running', 'A balanced ride to help kick-start your run, the Winflo 10 is great whether you re a creature of habit logging your weekly mileage, a rookie hoping to turn intrigue into routine or just joining a friend for a spontaneous weekend run. ', 'Nike Winflo 10 Premium2.webp', 'Nike Winflo 10 Premium1.webp', 'Nike Winflo 10 Premium3.webp', 'available'),
(130, 'Nike Zoom Mercurial Vapor 15 Elite XXV FG', 'Nike', '1149.00', 'Metallic Silver/Black/Volt', 'Female', ' Football', 'Celebrate the 25th anniversary of 1 of our fastest boots ever created with the Vapor 15 Elite XXV. Iconic colours synonymous with the Mercurial complement hints of its decorated history. The stunning chrome effect puts even more electricity into these lig', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG 1.webp', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG 2.jpg', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG 3.webp', 'available'),
(131, 'Nike React Gato', 'Nike', '579.00', 'Blackened Blue/Volt/Black/White', 'Female', ' Football', 'The Nike React Gato brings a new level of underfoot control and cushioning to the court. Flexible pods improve your feel on the ball and Nike React cushioning keeps you moving as you drag and cut across the court.', 'Nike React Gato1.webp', 'Nike React Gato2.jpg', 'Nike React Gato3.webp', 'available'),
(132, 'The Nike Premier 3 FG', 'Nike', '479.00', 'Black/White', 'Female', ' Football', 'The Nike Premier 3 gets back to the basics with a timeless silhouette wrapped in supple natural leather—helping you to move confidently as an unstoppable force on the pitch.', 'The Nike Premier 3 FG1.webp', 'The Nike Premier 3 FG2.webp', 'The Nike Premier 3 FG3.webp', 'available'),
(133, 'Nike Air Force 1 07 FlyEase', 'Nike', '489', 'White', 'Female', ' Sneaker', 'Quicker than 1, 2, 3—the original hoops shoe lets you step in and get going. Its easy-entry FlyEase system gives you a hands-free experience. Crisp leather dons the cleanest colour for the ultimate wearability. Yeah, its everything you love and then some.', 'Nike Air Force 1 07 FlyEase1.webp', 'Nike Air Force 1 07 FlyEase2.webp', 'Nike Air Force 1 07 FlyEase3.webp', 'available'),
(134, 'Nike Pegasus Trail 4', 'Nike', '559.00', 'Dark Pony/Picante Red/Earth/Sail', 'Female', ' Sneaker', 'Running is your daily ritual, taking you from road to trail as you seek out new adventures and goals. Let the Nike Pegasus Trail 4 take you there and back again with its supportive and springy feel. Underfoot traction keeps you going over rocky terrain, w', 'Nike Pegasus Trail 41.jpg', 'Nike Pegasus Trail 42.webp', 'Nike Pegasus Trail 43.webp', 'available'),
(135, 'Nike Team Hustle D 11', 'Nike', '245.00', 'White/White/Phantom/Black', 'Kid', ' Basketball', 'Ready for new basketball shoes? How about for gym class or just playing outside? Meet the Team Hustle D 11. With this edition of our Team Hustle D series, our top priority is making a shoe thats super-easy to get on and off. Elastic laces give you a wide ', 'Nike Team Hustle D 111.webp', 'Nike Team Hustle D 112.webp', 'Nike Team Hustle D 113.webp', 'available'),
(136, 'Nike Team Hustle D 11W', 'Nike', '245.00', 'White/Black', 'Kid', ' Basketball', 'Ready for new basketball shoes? How about for gym class or just playing outside? Meet the Nike Team Hustle D 11. With this edition of our Team Hustle D series, our top priority is kicks that are super easy to get on and off. Elastic laces give you a wide ', 'Nike Team Hustle D 11W1.webp', 'Nike Team Hustle D 11W2.webp', 'Nike Team Hustle D 11W3.webp', 'available'),
(137, 'Giannis Immortality 2', 'Nike', '215.00', 'BLACK', 'Kid', ' Basketball', 'All little hoopers want playtime to last forever. The Giannis Immortality 2 helps make that happen. Rocking a new-and-improved strap for comfort (stamped with Giannis logo) and a lightweight feel, these shoes make sure the fun never stops.', 'Giannis Immortality 21.webp', 'Giannis Immortality 22.webp', 'Giannis Immortality 23.webp', 'available'),
(138, 'Nike Flex Plus 2', 'Nike', '245.00', 'Photon Dust/Gridiron/Citron Pulse/Laser Orange', 'Kid', ' Running', 'The Nike Flex Plus 2 wastes no time so you can get out the door to run and play. We re talking break time, PE class and all of your favourite activities. The innovative elastic band system makes getting these shoes on a breeze. They re breathable and dura', 'Nike Flex Plus 21.webp', 'Nike Flex Plus 22.webp', 'Nike Flex Plus 23.webp', 'available'),
(139, 'adidas Ultraboost Light', 'Adidas', '869.00', 'Pink Shock / Black', 'Male', ' Running', 'Experience epic energy with the new Ultraboost Light, our lightest Ultraboost ever. The magic lies in the Light BOOST midsole, a new generation of adidas BOOST. Its unique molecule design achieves the lightest BOOST foam to date. With hundreds of BOOST ca', 'adidas Ultraboost Light1.webp', 'adidas Ultraboost Light2.webp', 'adidas Ultraboost Light3.webp', 'available'),
(140, 'adidas Ultraboost 1.0', 'Adidas', '829.00', 'Gey', 'Male', ' Running', 'From a walk in the park to a weekend run with friends, these adidas Ultraboost 1.0 shoes are designed to keep you comfortable. An adidas PRIMEKNIT upper gently hugs your feet while BOOST on the midsole cushions from the first step to the last mile. The St', 'adidas Ultraboost 1.01.webp', 'adidas Ultraboost 1.02.webp', 'adidas Ultraboost 1.03.webp', 'available'),
(141, 'adidas Ultraboost 23', 'Adidas', '869.00', 'Whie', 'Male', ' Running', 'The upper, crafted with high-performance yarn that contains at least 50% Parley Ocean Plastic and 50% recycled polyester, helps you tread lightly on the planet. Set boundaries and push right through them in the adidas Ultraboost 23.', 'adidas Ultraboost 231.webp', 'adidas Ultraboost 232.webp', 'adidas Ultraboost 233.webp', 'available'),
(142, 'adidas Ultraboost 1.0 Womens', 'Adidas', '829.00', 'Black', 'Female', ' Running', 'From a walk in the park to a weekend run with friends, these adidas Ultraboost 1.0 shoes are designed to keep you comfortable. An adidas PRIMEKNIT upper gently hugs your feet, and BOOST on the midsole cushions from the first step to the last mile.', 'adidas Ultraboost 1.0 Womens1.webp', 'adidas Ultraboost 1.0 Womens2.webp', 'adidas Ultraboost 1.0 Womens3.webp', 'available'),
(143, 'adidas Duramo 10 Women', 'Adidas', '269.00', 'BLACK/WHITE', 'Female', ' Running', 'Made with a series of recycled materials, this upper features at least 50% recycled content. This product represents just one of our solutions to help end plastic waste.', 'adidas Duramo 10 Women1.webp', 'adidas Duramo 10 Women2.webp', 'adidas Duramo 10 Women3.webp', 'available'),
(144, 'Converse Chuck Taylor All Star Ox', 'Converse', '269.00', 'WHITE', 'Male', ' Running', 'Finished with Converse branding to the tongue and heel, the All Star has carried the torch for individual style for close to one hundred years, and the flame doesnt look to be going out any time soon.', 'Converse Chuck Taylor All Star Ox1.webp', 'Converse Chuck Taylor All Star Ox2.webp', 'Converse Chuck Taylor All Star Ox3.webp', 'available'),
(145, 'Converse Chuck Taylor All Star OxW', 'Converse', '269.00', 'BLACK', 'Male', ' Running', 'Other features include: All Star branding on the tongue and heel, metal eyelets on the lace up and white double stitching throughout.', 'Converse Chuck Taylor All Star OxW1.webp', 'Converse Chuck Taylor All Star OxW2.webp', 'Converse Chuck Taylor All Star OxW3.webp', 'available'),
(146, 'Converse Run Star Hike Womens', 'Converse', '469.00', 'white', 'Female', ' Sneaker', 'With a split panelled, two-tone jagged outsole to keep you gripped, these statement sneakers are finished with the iconic rubber toe cap and Chuck Taylor patch to the sidewalls.', 'Converse Run Star Hike Womens1.webp', 'Converse Run Star Hike Womens2.webp', 'Converse Run Star Hike Womens3.webp', 'available'),
(147, 'Converse STAR PLAYER 76', 'Converse', '375.00', 'blue', 'Female', ' Sneaker', 'Underfoot, they sit on OrthoLite cushioning for a smooth ride, while the grippy rubber outsole delivers essential traction from first step to last. Finished up with iconic Star Chevron Converse branding to the sidewalls and bumper plate.', 'Converse STAR PLAYER 761.webp', 'Converse STAR PLAYER 762.webp', 'Converse STAR PLAYER 763.webp', 'available'),
(148, 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', 'Puma', '749.00', 'gold', 'Male', ' Basketball', 'DEAR CUSTOMER We wish to urge you to check the content of your received parcel carefully before signing the courier service consignment note, this apply to delivery to your address and self-collection at courier service office. Your kind attention and und', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE1.png', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE2.png', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE3.png', 'available'),
(149, 'MB.02 PUMA BLACK-SUNSET GLOW', 'Puma', '699.00', 'black/red', 'Male', ' Basketball', 'DEAR CUSTOMER We wish to urge you to check the content of your received parcel carefully before signing the courier service consignment note, this apply to delivery to your address and self-collection at courier service office. Your kind attention and und', 'MB.02 PUMA BLACK-SUNSET GLOW1.png', 'MB.02 PUMA BLACK-SUNSET GLOW2.png', 'MB.02 PUMA BLACK-SUNSET GLOW3.png', 'available'),
(150, 'White Converse Weapon CX Mid Vintage', 'Converse', '568.0', 'white', 'Male', ' Basketball', 'The on-court icon from 1986, completely transformed for whats next. A marbled ink screenprint featuring 90s-inspired, nostalgic color pops from the collar and Star Chevron. The premium leather upper meets the PU foam collar and wrapped, color-popped rubbe', 'White Converse Weapon CX Mid Vintage1.jpg', 'White Converse Weapon CX Mid Vintage2.jpg', 'White Converse Weapon CX Mid Vintage3.jpg', 'available'),
(151, 'Black Converse All Star BB Shift Low Top', 'Converse', '793.00', 'BLACK', 'Male', ' Basketball', 'The on-court icon from 1986, completely transformed for whats next. A marbled ink screenprint featuring 90s-inspired, nostalgic color pops from the collar and Star Chevron. The premium leather upper meets the PU foam collar and wrapped, color-popped rubbe', 'Black Converse All Star BB Shift Low Top1.jpg', 'Black Converse All Star BB Shift Low Top2.jpg', 'Black Converse All Star BB Shift Low Top3.jpg', 'available'),
(152, 'White Converse Chuck Taylor All Star Classic', 'Converse', '131.00', 'white', 'Kid', ' Sneaker', 'The unmistakable Chuck Taylor All Star Classic celebrates the iconic low top silhouette presented in a range of classic colours, and now the next generation can start their journey. No one will blame you for getting more than one pair.', 'White Converse Chuck Taylor All Star Classic1.jpg', 'White Converse Chuck Taylor All Star Classic2.jpg', 'White Converse Chuck Taylor All Star Classic3.jpg', 'available'),
(153, 'Black Converse Chuck Taylor All Star Classic Low', 'Converse', '131.00', 'BLACK', 'Kid', ' Sneaker', 'The unmistakable Chuck Taylor All Star Classic celebrates the iconic low top silhouette presented in a range of classic colours, and now the next generation can start their journey. No one will blame you for getting more than one pair.', 'Converse Chuck Taylor All Star OxW1.webp', 'Converse Chuck Taylor All Star OxW2.webp', 'Converse Chuck Taylor All Star OxW3.webp', 'available'),
(154, 'Softride Enzo Evo Running Shoes', 'Puma', '379.00', 'RED', 'Male', ' Running', 'A perfect pairing for the perfect pair – our Softride and Enzo franchises combine in this high-energy shoe. The technical cushioning in the footbed and sockliner optimises you', 'Softride Enzo Evo Running Shoes1.webp', 'Softride Enzo Evo Running Shoes2.webp', 'Softride Enzo Evo Running Shoes3.webp', 'available'),
(155, 'Carina 2.0 Sneakers Women', 'Puma', '329.00', 'white', 'Female', ' Sneaker', 'Introducing the Carina 2.0. Taking its design cues from the 80s, this classic tennis silhouette feels right at home with the laid-back sneaker style of Californian beach town', 'Carina 2.0 Sneakers Women1.webp', 'Carina 2.0 Sneakers Women2.webp', 'Carina 2.0 Sneakers Women3.webp', 'available'),
(156, 'Carina 2.0 Sneakers Women B', 'Puma', '329.00', 'White/Black', 'Female', ' Sneaker', 'Introducing the Carina 2.0. Taking its design cues from the 80s, this classic tennis silhouette feels right at home with the laid-back sneaker style of Californian beach town', 'Carina 2.0 Sneakers Women B1.webp', 'Carina 2.0 Sneakers Women B2.webp', 'Carina 2.0 Sneakers Women B3.webp', 'available'),
(157, 'PUMA Evolve Slip-On Sneakers Kids', 'Puma', '125.00', 'BLUE', 'Kid', ' Sneaker', 'Kids keep it simple in these sneakers that put the fun in functional. Featuring an easy on-off slip-on design for comfort and convenience', 'PUMA Evolve Slip-On Sneakers Kids1.jpg', 'PUMA Evolve Slip-On Sneakers Kids2.webp', 'PUMA Evolve Slip-On Sneakers Kids3.webp', 'available'),
(158, 'PUMA Evolve Slip-On Sneakers Kids P', 'Puma', '89.00', 'PINK', 'Kid', ' Sneaker', 'Kids keep it simple in these sneakers that put the fun in functional. Featuring an easy on off slip on design for comfort and convenience, they re the perfect fit for little o', 'PUMA Evolve Slip-On Sneakers Kids P1.webp', 'PUMA Evolve Slip-On Sneakers Kids P2.jpg', 'PUMA Evolve Slip-On Sneakers Kids P3.webp', 'available'),
(159, 'SOLARBOOST 5 SHOES', 'Adidas', '649.00', 'Coral Fusion / Zero Metalic / Acid Orange', 'Female', ' Running', 'Any run. Any distance. You re always set to chase your goals with the adidas Solarboost 5. They re your workhorse running shoes, with all the support, cushioning and energy you need to build consistency or up your mileage. A full-length BOOST midsole retu', 'SOLARBOOST 5 SHOES1.webp', 'SOLARBOOST 5 SHOES2.jpg', 'SOLARBOOST 5 SHOES3.webp', 'available'),
(160, 'SOLARBOOST 5 SHOES2', 'Adidas', '649.00', 'Cloud White / Silver Violet / Coral Fusion', 'Female', ' Running', 'Any run. Any distance. You re always set to chase your goals with the adidas Solarboost 5. They re your workhorse running shoes, with all the support, cushioning and energy you need to build consistency or up your mileage. A full-length BOOST midsole retu', 'SOLARBOOST 5 SHOES21.webp', 'SOLARBOOST 5 SHOES22.webp', 'SOLARBOOST 5 SHOES23.webp', 'available'),
(161, 'PUREBOOST 22 SHOES', 'Adidas', '369.00', 'Cloud White / Core Black / Blue Dawn', 'Kid', ' Sneaker', 'A little bit of time and some simple gear. Thats all you need for a satisfying run. Lace up in these juniors running shoes and head out for a few carefree kilometres. BOOST cushioning returns energy with every step, and the foot-hugging adidas PRIMEKNIT u', 'PUREBOOST 22 SHOES1.webp', 'PUREBOOST 22 SHOES2.jpg', 'PUREBOOST 22 SHOES3.webp', 'available'),
(162, 'Vans OFF White', 'Vans', '300', 'Grey', 'Male', 'Sneaker', 'Vans OFF THE WALL ', 'VN0009QSGRY1_VN0009QSGRY1_01.jpg', 'VN0009QSGRY1_VN0009QSGRY1_02.jpg', 'VN0009QSGRY1_VN0009QSGRY1_03.jpg', 'available'),
(163, 'VANS X SESAME STREET SKATE OLD SKOOL', 'Vans', '379.00', 'SESAME STREET MULTI', 'Male', 'Sneaker', 'Vans x Sesame Street Skate Old Skool\r\nMATERIAL\r\nLeather\r\n', 'VN000D3HBMB1_2.jpg', 'VN000D3HBMB1_1.jpg', 'VN000D3HBMB1_3.jpg', 'available'),
(164, 'OLD SKOOL', 'Vans', '299.00', 'BLACK/WHITE', 'Male', 'Basketball', 'OVERVIEW\r\nThe Old Skool, Vans classic skate shoe and the first to bare the iconic side stripe, has a low-top lace-up silhouette with a durable suede and canvas upper with padded tongue and lining and Vans signature Waffle Outsole.\r\nMATERIAL\r\nLeather', 'VN000D3HY281_OldSkool_1.jpg', 'VN000D3HY281_OldSkool_2.jpg', 'VN000D3HY281_OldSkool_3.jpg', 'available'),
(165, 'VANS X HARIBO CLASSIC SLIP-ON', 'Vans', '379.00', 'HARIBO CHECKERBOARD MULTI', 'Male', 'Basketball', 'OVERVIEW\r\nEnter the happy world of Haribo\r\n\r\nVans and Haribo come together for a collection of footwear and apparel that celebrates the childlike happiness of the worlds most popular gummy candy.\r\n\r\nFeaturing Haribos unmistakable gummy candies atop an all', 'VN0A7Q5DBMB1_2.jpg', 'VN0A7Q5DBMB1_1.jpg', 'VN0A7Q5DBMB1_3.jpg', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `promote_code`
--

CREATE TABLE `promote_code` (
  `id` int(255) NOT NULL,
  `Promote_Code` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `Expire_Date` varchar(255) NOT NULL,
  `Promote_type` varchar(255) NOT NULL,
  `Details` varchar(255) NOT NULL,
  `PromoteCode_Status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promote_code`
--

INSERT INTO `promote_code` (`id`, `Promote_Code`, `Value`, `Expire_Date`, `Promote_type`, `Details`, `PromoteCode_Status`) VALUES
(2, '727165', '10', '2024-12-19', 'Percentage Off', '10%OFF', 'Available'),
(3, '929551', '20', '2023-07-20', 'Value Off', 'RM20 OFF', 'Available'),
(4, '379085', '0', '2023-08-26', 'Free Shipping', 'Free Shipping', 'Available'),
(5, '433932', '40', '2023-06-14', 'Percentage Off', '40%off', 'expired'),
(6, '967772', '30', '2023-07-14', 'Value Off', 'RM30 OFF', 'Available'),
(7, '353948', '50', '2023-06-15', 'Value Off', 'RM50 OFF', 'expired'),
(8, '500216', '25', '2023-06-30', 'Percentage Off', '25%OFF', 'Available'),
(9, 'SP726Canival', '10', '2023-06-26', 'Percentage Off', 'RM10 OFF', 'expired'),
(10, '301950', '20', '2023-06-22', 'Value Off', 'RM20 OFF', 'expired'),
(11, '183318', '5', '2023-06-26', 'Percentage Off', '5% off', 'expired'),
(12, '926947', '10', '2023-06-26', 'Value Off', 'RM 10 off', 'expired'),
(13, '237680', '12', '2023-06-30', 'Value Off', 'RM 12 off', 'Availale');

-- --------------------------------------------------------

--
-- Table structure for table `sizeandqty`
--

CREATE TABLE `sizeandqty` (
  `ID` int(255) NOT NULL,
  `Prod_ID` varchar(255) NOT NULL,
  `Prod_Name` varchar(255) NOT NULL,
  `Prod_Size` varchar(255) NOT NULL,
  `Prod_Qty` varchar(255) NOT NULL,
  `Qty_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizeandqty`
--

INSERT INTO `sizeandqty` (`ID`, `Prod_ID`, `Prod_Name`, `Prod_Size`, `Prod_Qty`, `Qty_status`) VALUES
(16, '', 'nike running shoes', '6', '56', 'Available'),
(17, '', 'nike running shoes', '6.5', '8955', 'Available'),
(18, '', 'nike running shoes', '7', '89', 'Available'),
(19, '', 'nike running shoes', '7.5', '56', 'Available'),
(20, '', 'nike running shoes', '8', '59', 'Available'),
(21, '', 'KD Trey 5 X EP W', '7', '56', 'Available'),
(22, '', 'KD Trey 5 X EP W', '7.5', '59', 'Available'),
(23, '', 'KD Trey 5 X EP W', '8', '573', 'Available'),
(24, '', 'KD Trey 5 X EP W', '8.5', '78', 'Available'),
(25, '', 'Select', '9', '75', 'Available'),
(26, '', 'KD Trey 5 X EP W', '9.5', '45', 'Available'),
(28, '', 'Tatum 1 W', '6', '832', 'Available'),
(29, '', 'Tatum 1 W', '6.5', '86', 'Available'),
(30, '', 'Tatum 1 W', '7', '86', 'Available'),
(31, '', 'Tatum 1 W', '7.5', '56', 'Available'),
(32, '', 'Tatum 1 W', '8', '86', 'Available'),
(33, '', 'Tatum 1 W', '8.5', '9', 'Available'),
(34, '', 'Tatum 1 W', '8.5', '9', 'Available'),
(35, '', 'KD TREY 5 X EP 101', '7.5', '57', 'Available'),
(36, '', 'KD TREY 5 X EP 101', '8', '674', 'Available'),
(37, '', 'KD TREY 5 X EP 101', '8.5', '689', 'Available'),
(38, '', 'KD TREY 5 X EP 101', '9', '246', 'Available'),
(39, '', 'KD TREY 5 X EP 101', '9.5', '579', 'Available'),
(40, '', 'KD TREY 5 X EP 101', '10', '245', 'Available'),
(41, '', 'KD15 EP 100', '7.5', '466', 'Available'),
(42, '', 'KD15 EP 100', '8', '467', 'Available'),
(43, '', 'KD15 EP 100', '8.5', '248', 'Available'),
(44, '', 'KD15 EP 100', '9', '578', 'Available'),
(45, '', 'KD15 EP 100', '9.5', '79', 'Available'),
(46, '', 'KD15 EP 100', '10.5', '424', 'Available'),
(47, '', 'KD15 EP 100', '10', '696', 'Available'),
(48, '', 'KD15 NRG EP', '8', '466', 'Available'),
(49, '', 'KD15 NRG EP', '8.5', '544', 'Available'),
(50, '', 'KD15 NRG EP', '9', '462', 'Available'),
(51, '', 'KD15 NRG EP', '9.5', '578', 'Available'),
(52, '', 'Nike Pegasus 40 Premium W', '8', '467', 'Available'),
(53, '', 'Nike Pegasus 40 Premium W', '8.5', '456', 'Available'),
(54, '', 'Nike Pegasus 40 Premium W', '9', '57', 'Available'),
(55, '', 'Nike Pegasus 40 Premium W', '9.5', '780', 'Available'),
(57, '', 'Nike Pegasus 40 Premium W', '10', '359', 'Available'),
(58, '', 'Nike Pegasus 40 Premium W', '10.5', '432', 'Available'),
(59, '', 'Nike Zegama R', '8', '31', 'Available'),
(60, '', 'Nike Zegama R', '8.5', '85', 'Available'),
(61, '', 'Nike Zegama R', '9', '77', 'Available'),
(62, '', 'Nike Zegama R', '9.5', '785', 'Available'),
(63, '', 'Nike Zegama R', '10', '563', 'Available'),
(64, '', 'Nike Zegama R', '10.5', '896', 'Available'),
(65, '', 'Nike Zegama R', '11', '89', 'Available'),
(66, '', 'Nike Zegama G', '7', '85', 'Available'),
(67, '', 'Nike Zegama G', '7.5', '75', 'Available'),
(68, '', 'Nike Zegama G', '8', '793', 'Available'),
(69, '', 'Nike Zegama G', '8.5', '134', 'Available'),
(70, '', 'Nike Zegama G', '9', '453', 'Available'),
(71, '', 'Nike Zegama G', '9.5', '45', 'Available'),
(72, '', 'Nike Zegama G', '10', '78', 'Available'),
(73, '', 'Nike Zegama G', '10.5', '453', 'Available'),
(74, '', 'Nike Zegama B', '8', '455', 'Available'),
(75, '', 'Nike Zegama B', '8.5', '853', 'Available'),
(76, '', 'Nike Zegama B', '9', '89', 'Available'),
(77, '', 'Nike Zegama B', '9.5', '123', 'Available'),
(78, '', 'Nike Zegama B', '10', '78', 'Available'),
(79, '', 'Nike Zegama B', '10.5', '12', 'Available'),
(80, '', 'Nike Rival Fly 3B', '7', '40', 'Available'),
(81, '', 'Nike Rival Fly 3B', '7.5', '632', 'Available'),
(82, '', 'Nike Rival Fly 3B', '8', '786', 'Available'),
(83, '', 'Nike Rival Fly 3B', '8.5', '12', 'Available'),
(84, '', 'Nike Rival Fly 3B', '9', '125', 'Available'),
(85, '', 'Nike Rival Fly 3B', '9.5', '453', 'Available'),
(86, '', 'Nike Phantom GX Club Dynamic Fit MG', '10.5', '45', 'Available'),
(87, '', 'Nike Phantom GX Club Dynamic Fit MG', '8.5', '475', 'Available'),
(88, '', 'Nike Phantom GX Club Dynamic Fit MG', '9', '88', 'Available'),
(89, '', 'Nike Phantom GX Club Dynamic Fit MG', '9.5', '48', 'Available'),
(90, '', 'Nike Phantom GX Club Dynamic Fit MG', '10', '423', 'Available'),
(91, '', 'Nike Mercurial Superfly 9 Club MG W', '7', '415', 'Available'),
(92, '', 'Nike Mercurial Superfly 9 Club MG W', '7.5', '45', 'Available'),
(93, '', 'Nike Mercurial Superfly 9 Club MG W', '8', '856', 'Available'),
(94, '', 'Nike Mercurial Superfly 9 Club MG W', '8.5', '41', 'Available'),
(95, '', 'Nike Mercurial Superfly 9 Club MG W', '9.5', '85', 'Available'),
(96, '', 'Nike Mercurial Superfly 9 Club MG W', '9', '142', 'Available'),
(97, '', 'Nike Mercurial Superfly 9 Club MG W', '10', '85', 'Available'),
(98, '', 'Nike Mercurial Superfly 9 Club MG B', '7', '58', 'Available'),
(99, '', 'Nike Mercurial Superfly 9 Club MG B', '7.5', '853', 'Available'),
(100, '', 'Nike Mercurial Superfly 9 Club MG B', '8', '85', 'Available'),
(101, '', 'Nike Mercurial Superfly 9 Club MG B', '8.5', '123', 'Available'),
(102, '', 'Nike Mercurial Superfly 9 Club MG B', '9', '96', 'Available'),
(103, '', 'Nike Mercurial Superfly 9 Club MG B', '9.5', '123', 'Available'),
(104, '', 'Nike Phantom GT2 Academy IC', '8', '412', 'Available'),
(105, '', 'Nike Phantom GT2 Academy IC', '8.5', '962', 'Available'),
(106, '', 'Nike Phantom GT2 Academy IC', '9', '45', 'Available'),
(107, '', 'Nike Phantom GT2 Academy IC', '9.5', '963', 'Available'),
(108, '', 'AIR FORCE 1 07W', '6', '41', 'Available'),
(109, '', 'AIR FORCE 1 07W', '6.5', '96', 'Available'),
(110, '', 'AIR FORCE 1 07W', '7', '78', 'Available'),
(111, '', 'AIR FORCE 1 07W', '7.5', '136', 'Available'),
(112, '', 'AIR FORCE 1 07W', '8', '896', 'Available'),
(113, '', 'AIR FORCE 1 07W', '8.5', '413', 'Available'),
(114, '', 'AIR FORCE 1 07W', '9', '74', 'Available'),
(115, '', 'AIR FORCE 1 07W', '9.5', '41', 'Available'),
(116, '', 'AIR FORCE 1 07W', '10', '897', 'Available'),
(117, '', 'AIR FORCE 1 07B', '7', '746', 'Available'),
(118, '', 'AIR FORCE 1 07B', '7.5', '963', 'Available'),
(119, '', 'AIR FORCE 1 07B', '8', '123', 'Available'),
(120, '', 'AIR FORCE 1 07B', '8.5', '45', 'Available'),
(121, '', 'AIR FORCE 1 07B', '9', '45', 'Available'),
(122, '', 'AIR FORCE 1 07B', '9.5', '453', 'Available'),
(123, '', 'AIR FORCE 1 07B', '10', '453', 'Available'),
(124, '', 'AIR JORDAN 3 RETRO (GS)', '9', '845', 'Available'),
(125, '', 'AIR JORDAN 3 RETRO (GS)', '9.5', '96', 'Available'),
(126, '', 'AIR JORDAN 3 RETRO (GS)', '10', '125', 'Available'),
(127, '', 'HARDEN VOL. 6', '8', '253', 'Available'),
(128, '', 'HARDEN VOL. 6', '8.5', '145', 'Available'),
(129, '', 'HARDEN VOL. 6', '9', '45', 'Available'),
(130, '', 'HARDEN VOL. 6', '9.5', '142', 'Available'),
(131, '', 'HARDEN VOL. 6', '10.5', '94', 'Available'),
(132, '', 'HARDEN VOL. 7', '8.5', '142', 'Available'),
(133, '', 'HARDEN VOL. 7', '9', '96', 'Available'),
(134, '', 'HARDEN VOL. 7', '9.5', '12', 'Available'),
(136, '', 'HARDEN VOL. 7', '10', '46', 'Available'),
(137, '', 'HARDEN VOL. 7', '10.5', '96', 'Available'),
(138, '', 'HARDEN VOL. 7', '11', '78', 'Available'),
(140, '', 'HARDEN VOL. 7', '11', '46', 'Available'),
(141, '', 'HARDEN VOL. 7b', '8.5', '76', 'Available'),
(142, '', 'HARDEN VOL. 7b', '9', '86', 'Available'),
(143, '', 'HARDEN VOL. 7b', '9.5', '86', 'Available'),
(144, '', 'HARDEN VOL. 7b', '10', '65', 'Available'),
(145, '', 'HARDEN VOL. 7b', '10.5', '468', 'Available'),
(146, '', 'HARDEN VOL. 7b', '12', '341', 'Available'),
(147, '', 'Nike Air Deldon Hoodie', '4.5', '496', 'Available'),
(148, '', 'Nike Air Deldon Hoodie', '5', '586', 'Available'),
(149, '', 'Nike Air Deldon Hoodie', '6', '123', 'Available'),
(150, '', 'Nike Air Deldon Hoodie', '6.5', '423', 'Available'),
(151, '', 'Nike Air Deldon Hoodie', '7', '453', 'Available'),
(153, '', 'Nike Air Deldon Hoodie', '7.5', '436', 'Available'),
(154, '', 'Nike Air Deldon Hoodie', '8', '125', 'Available'),
(155, '', 'Nike Air Deldon Hoodie', '8.5', '43', 'Available'),
(156, '', 'Nike Cosmic Unity 2W', '4.5', '412', 'Available'),
(157, '', 'Nike Cosmic Unity 2W', '5', '423', 'Available'),
(158, '', 'Nike Cosmic Unity 2W', '5.5', '863', 'Available'),
(159, '', 'Nike Cosmic Unity 2W', '6.5', '6', 'Available'),
(160, '', 'Nike Cosmic Unity 2W', '6', '496', 'Available'),
(161, '', 'Nike Cosmic Unity 2W', '7.5', '496', 'Available'),
(162, '', 'Nike Cosmic Unity 2W', '8.5', '413', 'Available'),
(163, '', 'Air Jordan XXXVII Low', '4', '245', 'Available'),
(171, '', 'Air Jordan XXXVII Low', '4.5', '112', 'Available'),
(172, '', 'Air Jordan XXXVII Low', '5', '663', 'Available'),
(173, '', 'Air Jordan XXXVII Low', '6', '965', 'Available'),
(174, '', 'Air Jordan XXXVII Low', '6.5', '123', 'Available'),
(176, '', 'Air Jordan XXXVII Low', '5.5', '965', 'Available'),
(177, '', 'Air Jordan XXXVII Low', '7', '445', 'Available'),
(178, '', 'Air Jordan XXXVII Low', '7.5', '653', 'Available'),
(179, '', 'Air Jordan XXXVII Low', '8', '987', 'Available'),
(180, '', ' Air Jordan 5 Retro', '4.5', '111', 'Available'),
(181, '', ' Air Jordan 5 Retro', '5', '965', 'Available'),
(182, '', ' Air Jordan 5 Retro', '5.5', '663', 'Available'),
(183, '', ' Air Jordan 5 Retro', '6', '222', 'Available'),
(184, '', ' Air Jordan 5 Retro', '6.5', '788', 'Available'),
(185, '', ' Air Jordan 5 Retro', '7', '111', 'Available'),
(186, '', ' Air Jordan 5 Retro', '7.5', '336', 'Available'),
(187, '', 'Nike x Ambush Air Adjust Force', '5.5', '236', 'Available'),
(188, '', 'Nike x Ambush Air Adjust Force', '6', '96', 'Available'),
(189, '', 'Nike x Ambush Air Adjust Force', '6.5', '213', 'Available'),
(190, '', 'Nike x Ambush Air Adjust Force', '7', '999', 'Available'),
(191, '', 'Nike Pegasus 40 SE', '3.5', '89', 'Available'),
(192, '', 'Nike Pegasus 40 SE', '4', '85', 'Available'),
(193, '', 'Nike Pegasus 40 SE', '4.5', '112', 'Available'),
(194, '', 'Nike Pegasus 40 SE', '5', '456', 'Available'),
(196, '', 'Nike Pegasus 40 SE', '5.5', '145', 'Available'),
(197, '', 'Nike Pegasus 40 SE', '6.5', '45', 'Available'),
(198, '', 'Nike Pegasus 40 SE', '7', '223', 'Available'),
(199, '', ' Nike Pegasus 40 Premium', '5', '563', 'Available'),
(200, '', ' Nike Pegasus 40 Premium', '5.5', '456', 'Available'),
(201, '', ' Nike Pegasus 40 Premium', '6', '365', 'Available'),
(202, '', ' Nike Pegasus 40 Premium', '6.5', '145', 'Available'),
(203, '', ' Nike Pegasus 40 Premium', '7', '896', 'Available'),
(204, '', ' Nike Pegasus 40 Premium', '7.5', '996', 'Available'),
(205, '', 'Nike Winflo 10 Premium', '3.5', '56', 'Available'),
(206, '', 'Nike Winflo 10 Premium', '4', '489', 'Available'),
(207, '', 'Nike Winflo 10 Premium', '4.5', '365', 'Available'),
(208, '', 'Nike Winflo 10 Premium', '5', '154', 'Available'),
(209, '', 'Nike Winflo 10 Premium', '5.5', '596', 'Available'),
(210, '', 'Nike Winflo 10 Premium', '6', '236', 'Available'),
(211, '', 'Nike Winflo 10 Premium', '6.5', '1856', 'Available'),
(212, '', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG', '5', '596', 'Available'),
(213, '', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG', '5.5', '365', 'Available'),
(214, '', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG', '6', '107', 'Available'),
(215, '', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG', '7', '96', 'Available'),
(216, '', 'Nike Zoom Mercurial Vapor 15 Elite XXV FG', '7.5', '365', 'Available'),
(217, '', 'Nike React Gato', '5', '263', 'Available'),
(218, '', 'Nike React Gato', '5.5', '231', 'Available'),
(219, '', 'Nike React Gato', '6', '124', 'Available'),
(220, '', 'Nike React Gato', '6.5', '632', 'Available'),
(221, '', 'Nike React Gato', '7', '332', 'Available'),
(222, '', 'Nike React Gato', '7.5', '112', 'Available'),
(223, '', 'Nike React Gato', '8', '85', 'Available'),
(224, '', 'The Nike Premier 3 FG', '6', '32', 'Available'),
(225, '', 'The Nike Premier 3 FG', '6.5', '46', 'Available'),
(226, '', 'The Nike Premier 3 FG', '5.5', '79', 'Available'),
(227, '', 'Nike Air Force 1 07 FlyEase', '3.5', '365', 'Available'),
(228, '', 'Nike Air Force 1 07 FlyEase', '4', '154', 'Available'),
(229, '', 'Nike Air Force 1 07 FlyEase', '4.5', '23', 'Available'),
(230, '', 'Nike Air Force 1 07 FlyEase', '5', '63', 'Available'),
(231, '', 'Nike Air Force 1 07 FlyEase', '5.5', '236', 'Available'),
(232, '', 'Nike Air Force 1 07 FlyEase', '6', '36', 'Available'),
(233, '', 'Nike Air Force 1 07 FlyEase', '6.5', '129', 'Available'),
(234, '', 'Nike Air Force 1 07 FlyEase', '7.5', '89', 'Available'),
(235, '', 'Nike Pegasus Trail 4', '5', '365', 'Available'),
(236, '', 'Nike Pegasus Trail 4', '5.5', '96', 'Available'),
(237, '', 'Nike Pegasus Trail 4', '6', '145', 'Available'),
(238, '', 'Nike Pegasus Trail 4', '7', '636', 'Available'),
(239, '', 'Nike Pegasus Trail 4', '7.5', '635', 'Available'),
(240, '', 'Nike Team Hustle D 11', '3.5', '365', 'Available'),
(241, '', 'Nike Team Hustle D 11', '4', '458', 'Available'),
(242, '', 'Nike Team Hustle D 11', '4.5', '96', 'Available'),
(243, '', 'Nike Team Hustle D 11', '5', '110', 'Available'),
(244, '', 'Nike Team Hustle D 11', '5.5', '102', 'Available'),
(245, '', 'Nike Team Hustle D 11', '6', '302', 'Available'),
(246, '', 'Nike Team Hustle D 11W', '3.5', '210', 'Available'),
(247, '', 'Nike Team Hustle D 11W', '4', '450', 'Available'),
(248, '', 'Nike Team Hustle D 11W', '4.5', '362', 'Available'),
(249, '', 'Nike Team Hustle D 11W', '5', '210', 'Available'),
(250, '', 'Nike Team Hustle D 11W', '5.5', '120', 'Available'),
(251, '', 'Giannis Immortality 2', '3.5', '250', 'Available'),
(252, '', 'Giannis Immortality 2', '4', '632', 'Available'),
(253, '', 'Giannis Immortality 2', '4.5', '250', 'Available'),
(254, '', 'Giannis Immortality 2', '5', '963', 'Available'),
(255, '', 'Nike Flex Plus 2', '3.5', '320', 'Available'),
(256, '', 'Nike Flex Plus 2', '4', '965', 'Available'),
(257, '', 'Nike Flex Plus 2', '4.5', '240', 'Available'),
(258, '', 'Nike Flex Plus 2', '5', '360', 'Available'),
(259, '', 'adidas Ultraboost Light', '7', '350', 'Available'),
(260, '', 'adidas Ultraboost Light', '7.5', '396', 'Available'),
(261, '', 'adidas Ultraboost Light', '8', '125', 'Available'),
(262, '', 'adidas Ultraboost Light', '8.5', '960', 'Available'),
(263, '', 'adidas Ultraboost Light', '9', '231', 'Available'),
(264, '', 'adidas Ultraboost Light', '9.5', '201', 'Available'),
(265, '', 'adidas Ultraboost 1.0', '6', '365', 'Available'),
(266, '', 'adidas Ultraboost 1.0', '7', '965', 'Available'),
(267, '', 'adidas Ultraboost 1.0', '7.5', '120', 'Available'),
(268, '', 'adidas Ultraboost 1.0', '8', '963', 'Available'),
(269, '', 'adidas Ultraboost 1.0', '8.5', '150', 'Available'),
(270, '', 'adidas Ultraboost 1.0', '9', '90', 'Available'),
(271, '', 'adidas Ultraboost 23', '6', '306', 'Available'),
(272, '', 'adidas Ultraboost 23', '7', '201', 'Available'),
(273, '', 'adidas Ultraboost 23', '8', '420', 'Available'),
(274, '', 'adidas Ultraboost 23', '10', '3620', 'Available'),
(275, '', 'adidas Ultraboost 1.0 Womens', '4', '360', 'Available'),
(276, '', 'adidas Ultraboost 1.0 Womens', '4.5', '230', 'Available'),
(277, '', 'adidas Ultraboost 1.0 Womens', '5', '301', 'Available'),
(278, '', 'adidas Ultraboost 1.0 Womens', '5.5', '301', 'Available'),
(279, '', 'adidas Ultraboost 1.0 Womens', '6', '960', 'Available'),
(280, '', 'adidas Duramo 10 Women', '4', '301', 'Available'),
(281, '', 'adidas Duramo 10 Women', '4.5', '903', 'Available'),
(282, '', 'adidas Duramo 10 Women', '5.5', '604', 'Available'),
(283, '', 'adidas Duramo 10 Women', '6', '320', 'Available'),
(284, '', 'adidas Duramo 10 Women', '6.5', '643', 'Available'),
(285, '', 'Converse Chuck Taylor All Star Ox', '8', '963', 'Available'),
(286, '', 'Converse Chuck Taylor All Star Ox', '8.5', '230', 'Available'),
(287, '', 'Converse Chuck Taylor All Star Ox', '9', '950', 'Available'),
(288, '', 'Converse Chuck Taylor All Star Ox', '7', '420', 'Available'),
(289, '', 'Converse Chuck Taylor All Star Ox', '7.5', '340', 'Available'),
(290, '', 'Converse Chuck Taylor All Star OxW', '7', '960', 'Available'),
(291, '', 'Converse Chuck Taylor All Star OxW', '7.5', '350', 'Available'),
(292, '', 'Converse Chuck Taylor All Star OxW', '8', '150', 'Available'),
(293, '', 'Converse Chuck Taylor All Star OxW', '8.5', '450', 'Available'),
(294, '', 'Converse Chuck Taylor All Star OxW', '9', '350', 'Available'),
(295, '', 'Converse Chuck Taylor All Star OxW', '9.5', '302', 'Available'),
(296, '', 'Converse Run Star Hike Womens', '4', '302', 'Available'),
(297, '', 'Converse Run Star Hike Womens', '4.5', '631', 'Available'),
(298, '', 'Converse Run Star Hike Womens', '6', '352', 'Available'),
(299, '', 'Converse Run Star Hike Womens', '7', '635', 'Available'),
(300, '', 'Converse STAR PLAYER 76', '6', '320', 'Available'),
(301, '', 'Converse STAR PLAYER 76', '7', '352', 'Available'),
(302, '', 'Converse STAR PLAYER 76', '7.5', '450', 'Available'),
(303, '', 'Converse STAR PLAYER 76', '8', '360', 'Available'),
(304, '', 'Converse STAR PLAYER 76', '8.5', '120', 'Available'),
(305, '', 'Converse STAR PLAYER 76', '9', '220', 'Available'),
(306, '', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', '8', '330', 'Available'),
(307, '', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', '8.5', '236', 'Available'),
(308, '', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', '9', '120', 'Available'),
(309, '', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', '9.5', '302', 'Available'),
(310, '', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', '10', '222', 'Available'),
(311, '', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', '11', '120', 'Available'),
(312, '', 'MB.02 PUMA BLACK-SUNSET GLOW', '9', '225', 'Available'),
(313, '', 'MB.02 PUMA BLACK-SUNSET GLOW', '11', '632', 'Available'),
(314, '', 'MB.02 PUMA BLACK-SUNSET GLOW', '12', '330', 'Available'),
(315, '', 'White Converse Weapon CX Mid Vintage', '7', '263', 'Available'),
(316, '', 'White Converse Weapon CX Mid Vintage', '7.5', '152', 'Available'),
(317, '', 'White Converse Weapon CX Mid Vintage', '8', '296', 'Available'),
(318, '', 'White Converse Weapon CX Mid Vintage', '8.5', '630', 'Available'),
(319, '', 'White Converse Weapon CX Mid Vintage', '9', '120', 'Available'),
(320, '', 'White Converse Weapon CX Mid Vintage', '9.5', '120', 'Available'),
(321, '', 'White Converse Weapon CX Mid Vintage', '10', '550', 'Available'),
(322, '', 'Black Converse All Star BB Shift Low Top', '7', '65', 'Available'),
(323, '', 'Black Converse All Star BB Shift Low Top', '7.5', '230', 'Available'),
(324, '', 'Black Converse All Star BB Shift Low Top', '8', '410', 'Available'),
(325, '', 'Black Converse All Star BB Shift Low Top', '8.5', '210', 'Available'),
(326, '', 'Black Converse All Star BB Shift Low Top', '9', '220', 'Available'),
(327, '', 'Black Converse All Star BB Shift Low Top', '10', '963', 'Available'),
(328, '', 'White Converse Chuck Taylor All Star Classic', '3.5', '110', 'Available'),
(329, '', 'White Converse Chuck Taylor All Star Classic', '4.5', '320', 'Available'),
(330, '', 'White Converse Chuck Taylor All Star Classic', '4', '560', 'Available'),
(331, '', 'White Converse Chuck Taylor All Star Classic', '5', '220', 'Available'),
(332, '', 'MB.02 PUMA BLACK-SUNSET GLOW', '5.5', '320', 'Available'),
(333, '', 'White Converse Chuck Taylor All Star Classic', '6', '123', 'Available'),
(334, '', 'White Converse Chuck Taylor All Star Classic', '6.5', '330', 'Available'),
(335, '', 'Black Converse Chuck Taylor All Star Classic Low', '3.5', '56', 'Available'),
(336, '', 'Black Converse Chuck Taylor All Star Classic Low', '4', '330', 'Available'),
(337, '', 'Black Converse Chuck Taylor All Star Classic Low', '4.5', '140', 'Available'),
(338, '', 'Black Converse Chuck Taylor All Star Classic Low', '5.5', '320', 'Available'),
(339, '', 'Black Converse Chuck Taylor All Star Classic Low', '5', '102', 'Available'),
(340, '', 'Black Converse Chuck Taylor All Star Classic Low', '6', '96', 'Available'),
(341, '', 'Black Converse Chuck Taylor All Star Classic Low', '6.5', '630', 'Available'),
(342, '', 'Black Converse Chuck Taylor All Star Classic Low', '7', '302', 'Available'),
(343, '', 'Softride Enzo Evo Running Shoes', '6', '102', 'Available'),
(344, '', 'Softride Enzo Evo Running Shoes', '6.5', '320', 'Available'),
(345, '', 'Softride Enzo Evo Running Shoes', '7', '380', 'Available'),
(346, '', 'Softride Enzo Evo Running Shoes', '8', '110', 'Available'),
(347, '', 'Softride Enzo Evo Running Shoes', '8.5', '399', 'Available'),
(348, '', 'Softride Enzo Evo Running Shoes', '9', '220', 'Available'),
(349, '', 'Softride Enzo Evo Running Shoes', '10', '403', 'Available'),
(350, '', 'Carina 2.0 Sneakers Women', '4.5', '210', 'Available'),
(351, '', 'Carina 2.0 Sneakers Women', '5', '332', 'Available'),
(353, '', 'Carina 2.0 Sneakers Women', '5.5', '330', 'Available'),
(354, '', 'Carina 2.0 Sneakers Women', '6', '701', 'Available'),
(355, '', 'Carina 2.0 Sneakers Women', '7', '10', 'Available'),
(356, '', 'Carina 2.0 Sneakers Women B', '4.5', '310', 'Available'),
(357, '', 'Carina 2.0 Sneakers Women B', '5', '461', 'Available'),
(358, '', 'Carina 2.0 Sneakers Women B', '5.5', '203', 'Available'),
(359, '', 'Carina 2.0 Sneakers Women B', '6', '440', 'Available'),
(360, '', 'Carina 2.0 Sneakers Women B', '6.5', '330', 'Available'),
(361, '', 'Carina 2.0 Sneakers Women B', '7', '330', 'Available'),
(362, '', 'PUMA Evolve Slip-On Sneakers Kids', '3.5', '210', 'Available'),
(363, '', 'PUMA Evolve Slip-On Sneakers Kids', '4', '360', 'Available'),
(364, '', 'PUMA Evolve Slip-On Sneakers Kids', '4.5', '250', 'Available'),
(365, '', 'PUMA Evolve Slip-On Sneakers Kids', '5', '220', 'Available'),
(366, '', 'PUMA Evolve Slip-On Sneakers Kids', '5.5', '320', 'Available'),
(368, '', 'PUMA Evolve Slip-On Sneakers Kids', '6', '110', 'Available'),
(369, '', 'PUMA Evolve Slip-On Sneakers Kids P', '3.5', '210', 'Available'),
(370, '', 'PUMA Evolve Slip-On Sneakers Kids P', '4', '250', 'Available'),
(371, '', 'PUMA Evolve Slip-On Sneakers Kids P', '4.5', '330', 'Available'),
(372, '', 'PUMA Evolve Slip-On Sneakers Kids P', '5', '410', 'Available'),
(373, '', 'PUMA Evolve Slip-On Sneakers Kids P', '5.5', '956', 'Available'),
(374, '', 'PUMA Evolve Slip-On Sneakers Kids P', '6', '220', 'Available'),
(375, '', 'SOLARBOOST 5 SHOES', '4.5', '352', 'Available'),
(376, '', 'SOLARBOOST 5 SHOES', '5', '410', 'Available'),
(377, '', 'SOLARBOOST 5 SHOES', '5.5', '230', 'Available'),
(378, '', 'SOLARBOOST 5 SHOES', '6', '225', 'Available'),
(379, '', 'SOLARBOOST 5 SHOES', '6.5', '340', 'Available'),
(380, '', 'SOLARBOOST 5 SHOES', '7', '102', 'Available'),
(381, '', 'SOLARBOOST 5 SHOES2', '4.5', '220', 'Available'),
(382, '', 'SOLARBOOST 5 SHOES2', '5', '410', 'Available'),
(383, '', 'SOLARBOOST 5 SHOES2', '5.5', '360', 'Available'),
(384, '', 'SOLARBOOST 5 SHOES2', '6', '203', 'Available'),
(385, '', 'SOLARBOOST 5 SHOES2', '6.5', '220', 'Available'),
(386, '', 'PUREBOOST 22 SHOES', '3.5', '209', 'Available'),
(387, '', 'PUREBOOST 22 SHOES', '4', '330', 'Available'),
(388, '', 'PUREBOOST 22 SHOES', '4.5', '220', 'Available'),
(389, '', 'PUREBOOST 22 SHOES', '5.5', '250', 'Available'),
(390, '', 'PUREBOOST 22 SHOES', '6', '310', 'Available'),
(391, '', 'PUREBOOST 22 SHOES', '5', '140', 'Available'),
(392, '', 'PUREBOOST 22 SHOES', '7', '330', 'Available'),
(396, '', 'KD TREY 5 X EP 101', '7', '6', ''),
(397, '', 'Vans OFF White', '7', '190', ''),
(398, '', 'OLD SKOOL', '8', '50', 'Available'),
(399, '', 'OLD SKOOL', '8.5', '80', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `SC_ID` int(255) NOT NULL,
  `S_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`SC_ID`, `S_Name`) VALUES
(1, 'Basketball'),
(2, 'Football'),
(3, 'Sneaker'),
(7, 'Running');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wish_id` int(255) NOT NULL,
  `UID` varchar(255) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `Pimg1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wish_id`, `UID`, `prod_name`, `Pimg1`) VALUES
(47, '88', 'Nike Zegama R', ''),
(55, '91', 'PUREBOOST 22 SHOES', ''),
(56, '91', 'SOLARBOOST 5 SHOES2', ''),
(68, '91', 'PUMA Evolve Slip-On Sneakers Kids', ''),
(69, '91', 'Softride Enzo Evo Running Shoes', ''),
(70, '94', 'MB.01 DIGITAL PALE KHAKI-ULTRA ORANGE', ''),
(76, '91', 'Vans OFF White', ''),
(80, '101', 'Nike Zegama B', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`Brand_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oder`
--
ALTER TABLE `oder`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `oder_address`
--
ALTER TABLE `oder_address`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `oder_details`
--
ALTER TABLE `oder_details`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Prod_ID`);

--
-- Indexes for table `promote_code`
--
ALTER TABLE `promote_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizeandqty`
--
ALTER TABLE `sizeandqty`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`SC_ID`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wish_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `Brand_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `oder`
--
ALTER TABLE `oder`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `oder_address`
--
ALTER TABLE `oder_address`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `oder_details`
--
ALTER TABLE `oder_details`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Prod_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `promote_code`
--
ALTER TABLE `promote_code`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sizeandqty`
--
ALTER TABLE `sizeandqty`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=406;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `SC_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wish_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
