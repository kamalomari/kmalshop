-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 31, 2017 at 04:02 PM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `MaxIDUser` (IN `MaxID` INT)  BEGIN 
SELECT * FROM users
WHERE UserID <= MaxID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'ELECTRONICS', 'department all types electronics ', 0, 1, 1, 1, 1),
(2, 'VEHICLES', 'department all types vehicles', 0, 2, 0, 0, 0),
(3, 'IMMOVABLES', 'department all types immovables', 0, 3, 0, 0, 0),
(4, 'CLOTHING', 'department all types clothing', 0, 4, 0, 0, 0),
(5, 'CRYPTO_CURRENCY', 'department all types CryptoCurrency sell&buy online or Face-To-Face\n', 0, 6, 0, 0, 0),
(10, 'phones and tablets', 'cell phone and tablets', 1, 12, 0, 0, 0),
(11, 'cars', 'all types cars', 2, 21, 0, 0, 0),
(12, 'games', 'games play stations and Xbox and PC', 1, 11, 0, 0, 0),
(13, 'bitcoin', 'bitcoin is first crypto currency in the world\r\n', 5, 51, 0, 0, 0),
(14, 'apartement', 'apartements', 3, 31, 0, 0, 0),
(15, 'women', 'clothing women', 4, 41, 0, 0, 0),
(16, 'men', 'men clothing', 4, 42, 0, 0, 0),
(17, 'girls', 'girls clothing', 4, 43, 0, 0, 0),
(18, 'boys', 'boys clothing', 4, 44, 0, 0, 0),
(19, 'accessories', 'accessories electronic', 1, 13, 0, 0, 0),
(20, 'home furniture', 'home furniture', 3, 32, 0, 0, 0),
(21, 'laptops and desktop', 'laptop computer and desktop computer', 1, 14, 0, 0, 0),
(22, 'televisions', 'all types televisions ', 1, 15, 0, 0, 0),
(23, 'motorcycles', 'all types motorcycles', 2, 22, 0, 0, 0),
(24, 'bicycles', 'all types bicycles', 2, 23, 0, 0, 0),
(25, 'houses and villas', 'al types houses and villas', 3, 33, 0, 0, 0),
(26, 'ethereum', 'ethereum is second cryptocurrency in the world', 5, 52, 0, 0, 0),
(27, 'ripple', 'ripple is new cryptocurrency and beautiy', 5, 53, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(1, 'Very Nice', 1, '2016-05-11', 15, 28),
(2, 'Nice Item Thanks so much', 1, '2016-05-11', 18, 28),
(5, 'This Is Very Good Phone', 1, '2016-06-17', 16, 24),
(7, 'Very Cool', 1, '2016-06-17', 18, 25),
(8, 'Very Nice This Is The Second Comment', 1, '2016-06-17', 18, 25),
(9, 'This Is Me Turki', 1, '2016-06-17', 18, 30),
(10, 'Cool', 1, '2016-06-17', 15, 30),
(11, 'Helllo Comment', 1, '2016-06-17', 22, 30),
(12, '&#34;.&lt;h1&gt;kamal&lt;&sol;h1&gt;.&#34;', 0, '2017-09-28', 15, 47),
(13, 'kamalal', 0, '2017-09-28', 15, 47),
(14, 'kamalal', 0, '2017-09-28', 15, 47),
(15, 'kamalal', 1, '2017-09-28', 15, 47),
(16, 'kamalal', 0, '2017-09-28', 15, 47),
(17, 'kamalal', 0, '2017-09-28', 15, 47),
(18, 'kamalal', 0, '2017-09-28', 15, 47),
(19, 'kamalal', 0, '2017-09-28', 15, 47),
(20, 'kamalal', 0, '2017-09-28', 15, 47),
(21, 'kamalal', 0, '2017-09-28', 15, 47),
(22, 'kamalal', 0, '2017-09-28', 15, 47),
(23, 'nice item', 0, '2017-10-05', 29, 55),
(24, 'nice item', 0, '2017-10-05', 29, 55),
(28, 'Nice kamal omari brit naxri hd 3ayat hna 00555', 1, '2017-10-08', 21, 56),
(29, 'Nice kamal omari brit naxri hd 3ayat hna 00555', 0, '2017-10-08', 21, 56),
(30, 'Nice kamal omari brit naxri hd 3ayat hna 00555', 0, '2017-10-08', 21, 56);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `count_views` int(11) NOT NULL DEFAULT '1',
  `Country_Made` varchar(255) NOT NULL,
  `Image1` varchar(255) NOT NULL,
  `Image2` varchar(255) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '1',
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `Paypal_Ac` varchar(255) NOT NULL,
  `currency` varchar(100) NOT NULL DEFAULT 'USD',
  `Service_shop_item` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `count_views`, `Country_Made`, `Image1`, `Image2`, `Quantity`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`, `Paypal_Ac`, `currency`, `Service_shop_item`) VALUES
(14, 'Speaker', 'Very Good Speaker', '$10', '2016-05-09', 3, 'China', '', '', 1, '1', 0, 1, 11, 47, '', '', 'USD', '0'),
(15, 'Yeti Blue Mic', 'Very Good Microphone Very Good Microphone', '$108', '2016-05-09', 10, 'USA', '', '', 1, '1', 0, 1, 11, 47, '', '', 'USD', '0'),
(16, 'iPhone 6s', 'Apple iPhone 6s', '$300', '2016-05-09', 1, 'USA', '', '', 1, '2', 0, 1, 3, 24, '', '', 'USD', '0'),
(17, 'Magic Mouse', 'Apple Magic Mouse', '$150', '2016-05-09', 1, 'USA', '', '', 1, '1', 0, 1, 2, 24, '', '', 'USD', '0'),
(18, 'Network Cable', 'Cat 9 Network Cable', '$100', '2016-05-09', 1, 'USA', '', '', 1, '1', 0, 1, 11, 25, '', '', 'USD', '0'),
(19, 'Game', 'Test Game For Item', '120', '2016-06-17', 1, 'USA', '', '', 1, '2', 0, 1, 2, 30, '', '', 'USD', '0'),
(20, 'iPhone 6s', 'iPhone 6s Very Cool Phone', '1500', '2016-06-17', 1, 'USA', '', '', 1, '2', 0, 1, 3, 30, '', '', 'USD', '0'),
(21, 'Hammer', 'A Very Good Iron Hammer', '30', '2016-06-17', 1, 'China', '', '', 1, '3', 0, 1, 19, 30, '', '', 'USD', '0'),
(22, 'Good Box', 'Nice Hand Made Box', '40', '2016-06-17', 4, 'Egypt', '', '', 1, '1', 0, 1, 1, 30, '', '', 'USD', '0'),
(23, 'Test Item', 'This Is Test Item To Test Approve Status', '100', '2016-06-17', 1, 'Japan', '', '', 1, '3', 0, 1, 1, 30, '', '', 'USD', '0'),
(25, 'Osama', 'Osama Osama Elzero Elzero', '100', '2016-06-17', 1, 'Egypt', '', '', 1, '3', 0, 1, 3, 30, '', '', 'USD', '0'),
(26, '12121212', '33333333333', '33333', '2016-06-17', 1, '333333', '', '', 7, '2', 0, 1, 4, 30, '', '', 'USD', '0'),
(28, 'Wooden Game', 'A Good Wooden game', '100', '2016-07-25', 32, 'Egypt', 'a2.jpg', 'alien.jpg', 5, '1', 0, 1, 1, 30, 'Elzero, Hand, Discount, Gurantee', 'hamza.komidi@gmail.com', 'USD', '0'),
(29, 'Diablo III', 'Good Playstation 4 Game', '70', '2016-07-25', 1, 'USA', '', '', 1, '1', 0, 1, 12, 30, 'RPG, Online, Game', '', 'USD', '0'),
(30, 'Ys Oath In Felghana', 'A Good Ps Game', '100', '2016-07-25', 2, 'Japan', '', '', 1, '1', 0, 1, 12, 30, 'Online, RPG, Game', '', 'USD', '0'),
(31, 'galaxy s8', 'samsung galaxy s8', '1000', '2017-09-26', 1, 'morocco', '', '', 1, '1', 0, 1, 3, 47, NULL, '', 'USD', '0'),
(32, 'galaxy s8', 'samsung galaxy s8', '1000', '2017-09-26', 9, 'morocco', '', '', 1, '1', 0, 1, 3, 47, NULL, '', 'USD', '0'),
(34, 'Alienwaare', 'pc gamer original word 2017', '1000', '2017-09-30', 12, 'morocco', 'alien.jpg', 'a2.jpg', 1, '3', 0, 1, 2, 47, NULL, '', 'USD', '0'),
(35, 'Alienwaare', 'pc gamer original word 2017', '1000', '2017-09-30', 6, 'morocco', 'alien.jpg', 'a2.jpg', 1, '3', 0, 1, 11, 47, NULL, '', 'USD', '0'),
(36, 'Alienwaare', 'pc gamer original word 2017', '1000', '2017-09-30', 29, 'morocco', 'alien.jpg', 'a2.jpg', 7, '3', 0, 1, 11, 47, NULL, 'buyer12345@shop.com', 'USD', '0'),
(48, 'Samsung gear one', 'samsung gear smart watch new 2018', '200', '2017-10-07', 88, 'morocco', '414946671_125.jpg', '1008315959_aa.png', 5, '1', 0, 1, 3, 47, '', 'buyer12345@shop.com', 'USD', '0'),
(73, 'pictor', 'pictor is draw universes', '50', '2017-11-16', 259, 'morocco', '', '', 94, '1', 0, 1, 10, 56, 'Discount', 'seller12345@shop.com', 'USD', '0'),
(74, 'ReactJS', 'best lbrary javascript in facebbok', '50', '2017-11-22', 32, 'morocco', '77357279_anonymous-again.jpg', '286129178_bes.jpg', 83, '1', 0, 1, 12, 56, '', 'kamal@gmail.com', 'USD', 'paid'),
(78, 'kamal', 'jdhfudufdbufbdufudb', '1', '2017-12-27', 1, 'kdfidifdif', '512969660_kf3yo9.png', '', 55, '1', 0, 0, 12, 56, '', 'notPaypal', 'USD', 'noService');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `Id_trans_table` int(11) NOT NULL,
  `Id_trans_paypal` varchar(255) NOT NULL,
  `Id_product` int(11) NOT NULL,
  `Name_product` varchar(100) NOT NULL,
  `Sender_paypal` varchar(255) NOT NULL,
  `Receiver_paypal` varchar(255) NOT NULL,
  `User_pay` varchar(100) NOT NULL,
  `User_pay_id` varchar(100) NOT NULL,
  `User_sell` varchar(100) NOT NULL,
  `User_sell_id` varchar(100) NOT NULL,
  `User_phone` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Currency` varchar(100) NOT NULL,
  `Service_shop_pay` varchar(100) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `Status_final` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`Id_trans_table`, `Id_trans_paypal`, `Id_product`, `Name_product`, `Sender_paypal`, `Receiver_paypal`, `User_pay`, `User_pay_id`, `User_sell`, `User_sell_id`, `User_phone`, `Quantity`, `Currency`, `Service_shop_pay`, `Status`, `Date`, `Status_final`) VALUES
(1, '3KW752189L635823P', 48, 'Samsung gear one', 'kamalbuyer123@gmil.com', 'buyer12345@shop.com', 'Amine123', '56', 'Aplicat1234', '47', '', 1, 'USD', 'Standard Int\'l Shipping(0$)', 'Completed', '2017-12-12', 0),
(2, '5RT40972844104041', 36, 'Alienwaare', 'kamalbuyer123@gmil.com', 'buyer12345@shop.com', 'Amine123', '56', 'Aplicat1234', '47', '00212673568815', 3, 'USD', 'Standard Int\'l Shipping(0$)', 'Completed', '2017-12-17', 0),
(3, '6H64163712739014R', 28, 'Wooden Game', 'kamalbuyer123@gmil.com', 'hamza.komidi@gmail.com', 'Amine123', '56', 'Turki123', '30', '', 2, 'USD', 'Standard Int\'l Shipping(0$)', 'Completed', '2017-12-18', 0),
(4, '9YK939123F708142F', 74, 'ReactJS', 'kamalbuyer123@gmil.com', 'kamal@gmail.com', 'Aplicat1234', '47', 'Amine123', '56', '0673568815', 2, 'USD', 'Standard Int\'l Shipping(0$)', 'Completed', '2017-12-28', 1),
(5, '6E431453LX408354U', 74, 'ReactJS', 'kamalbuyer123@gmil.com', 'kamal@gmail.com', 'Aplicat1234', '47', 'Amine123', '56', '0673568815', 3, 'USD', 'Standard Int\'l Shipping(0$)', 'Completed', '2017-12-28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To Login',
  `First_N` varchar(100) NOT NULL,
  `Last_N` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL COMMENT 'Password To Login',
  `Email` varchar(255) NOT NULL,
  `VerifyEmail` int(11) NOT NULL DEFAULT '0',
  `Phone` varchar(50) NOT NULL,
  `VerifyPhone` int(11) NOT NULL DEFAULT '0',
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'Identify User Group',
  `TrustStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'User Approval',
  `Date` date NOT NULL,
  `Avatar` varchar(256) NOT NULL,
  `IP` varchar(50) NOT NULL,
  `Country` varchar(150) NOT NULL DEFAULT 'no',
  `City` varchar(150) NOT NULL,
  `Address1` varchar(255) NOT NULL,
  `Address2` varchar(255) NOT NULL,
  `Zip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `First_N`, `Last_N`, `Password`, `Email`, `VerifyEmail`, `Phone`, `VerifyPhone`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `Avatar`, `IP`, `Country`, `City`, `Address1`, `Address2`, `Zip`) VALUES
(1, 'Kamal123', '', '', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'kamal@gmail.com', 0, '', 0, 1, 0, 1, '0000-00-00', '', '', '', '', '', '', 0),
(24, 'Ahmed', '', '', '601f1889667efaebb33b8c12572835da3f027f78', 'ahmed@ahmed.com', 0, '', 0, 0, 0, 1, '2016-05-06', '', '', '', '', '', '', 0),
(25, 'Gamal', '', '', '601f1889667efaebb33b8c12572835da3f027f78', 'Gamal@mmm.com', 0, '', 0, 0, 0, 1, '2016-05-06', '', '', '', '', '', '', 0),
(27, 'Application12', '', '', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'app@appp.com', 0, '', 0, 0, 0, 1, '2016-05-11', '', '', '', '', '', '', 0),
(28, 'Khalid123', '', '', '601f1889667efaebb33b8c12572835da3f027f78', 'khokho@kh.com', 0, '', 0, 0, 0, 1, '2016-05-04', '', '', '', '', '', '', 0),
(30, 'Turki123', '', '', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'turki@gmail.com', 0, '', 0, 0, 0, 0, '2016-06-16', '', '', '', '', '', '', 0),
(31, 'kawtar', '', '', '601f1889667efaebb33b8c12572835da3f027f78', 'kawtar@gmail.com', 0, '', 0, 0, 0, 1, '2017-08-24', '', '', '', '', '', '', 0),
(41, 'koko', '', '', '601f1889667efaebb33b8c12572835da3f027f78', 'koko@gmail.com', 0, '', 0, 0, 0, 1, '0000-00-00', '', '', '', '', '', '', 0),
(43, 'Bamza123', '', '', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'bamza@gmail.com', 0, '', 0, 0, 0, 1, '0000-00-00', '', '', '', '', '', '', 0),
(45, 'Tata123', '', '', 'ddefdefc1f8566902a1618aadb178aceef412061', 'hamza.komidi123@gmail.com', 0, '', 0, 0, 0, 1, '2017-08-28', '', '', '', '', '', '', 0),
(47, 'Aplicat1234', 'aplicat', 'aplicat', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'hamza.komidi123@gmail.com', 0, '00212673568815', 0, 0, 0, 1, '2017-09-25', '', '127.0.0.1', 'no', '', '', '', 0),
(48, 'Hacker123', '', '', '057872b6d748ca6a6761ebd078763b42374f68f6', 'hacker@gmail.com', 0, '', 0, 0, 0, 1, '2017-10-04', '1163966651_fuck.jpg', '', '', '', '', '', 0),
(51, 'Hamza123', '', '', '601f1889667efaebb33b8c12572835da3f027f78', 'hamza@gmmail.com', 0, '0021256884575', 0, 0, 0, 1, '2017-10-05', '870016553_usa.jpg', '', '', '', '', '', 0),
(55, 'Anony123', '', '', 'a173e42ac9d410bcb2e4a476a35d40262d4c8752', 'anony@gmail.com', 0, '', 0, 0, 0, 1, '2017-10-05', '827931471_anon.jpg', '', '', '', '', '', 0),
(56, 'Amine123', 'kamal', 'amine', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'hamza.komidi123@gmail.com', 0, '0673568815', 1, 0, 0, 1, '2017-10-08', '803016070_usa.jpg', '::1', 'Morocco', 'casa', '2 N 9 ainaron blkhyat', 'strees 2 N 9 hy airaron', 30000),
(57, 'Hamaoch123', '', '', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'hamocj@gmail.com', 0, '006568487595', 0, 0, 0, 1, '2017-10-08', '1035172530_kf3yo9.png', '', '', '', '', '', 0),
(58, 'Bsila123', 'No!', 'No!', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'bsila@gmail.com', 0, '002126758584987', 0, 0, 0, 1, '2017-10-29', '1278086935_bes.jpg', '', '', '', '', '', 0),
(59, 'Ganon1234', 'simo', 'ganon', '54e2e5c9c1573b808bbfb2fc1459404371b88f4b', 'ganon@gmail.com', 0, '00212673568815', 0, 0, 0, 1, '2017-10-29', '', '192.168.1.6', '', '', '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `items_comment` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`Id_trans_table`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `Id_trans_table` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=60;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
