-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2017 at 01:45 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jstoromatic`
--

-- --------------------------------------------------------

--
-- Table structure for table `jst_advance_sale`
--

CREATE TABLE IF NOT EXISTS `jst_advance_sale` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `rate_string` text,
  `shop_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL COMMENT 'This will be updated after sale',
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_advance_sale`
--

INSERT INTO `jst_advance_sale` (`id`, `customer_id`, `rate_string`, `shop_id`, `sale_id`, `created_on`) VALUES
(2, 5, '1,24|2,20', 1, NULL, '2016-02-27 21:55:41'),
(3, 5, '1,24|2,20', 1, NULL, '2016-05-08 13:32:26');

-- --------------------------------------------------------

--
-- Table structure for table `jst_advance_sale_items`
--

CREATE TABLE IF NOT EXISTS `jst_advance_sale_items` (
  `id` int(11) NOT NULL,
  `advance_sale_id` int(11) DEFAULT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_type` enum('O','R','C') DEFAULT NULL COMMENT 'O for Ornament, R for Raw material, C for Cash',
  `purity` varchar(100) DEFAULT NULL,
  `weightoramt` varchar(100) DEFAULT NULL,
  `item_price_rating_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_advance_sale_items`
--

INSERT INTO `jst_advance_sale_items` (`id`, `advance_sale_id`, `item_name`, `item_type`, `purity`, `weightoramt`, `item_price_rating_id`) VALUES
(8, 2, 'Gold Ring', 'O', '2', '20', 1),
(9, 2, 'Silver Ring', 'O', '4', '5', 2),
(10, 2, 'Cash', 'C', '', '5000', 1),
(11, 3, 'Cash', 'C', '', '100', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jst_advertisement`
--

CREATE TABLE IF NOT EXISTS `jst_advertisement` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `emailtext` varchar(2000) DEFAULT NULL,
  `smstext` varchar(150) DEFAULT NULL,
  `isemail` enum('Y','N') DEFAULT NULL,
  `istext` enum('Y','N') DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_advertisment_broadcast_log`
--

CREATE TABLE IF NOT EXISTS `jst_advertisment_broadcast_log` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `communication_type` enum('S','E') DEFAULT NULL,
  `text` text,
  `sent_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_coupon`
--

CREATE TABLE IF NOT EXISTS `jst_coupon` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `couponcode` varchar(10) DEFAULT NULL,
  `typeofdiscount` enum('P','F') DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `on_which` enum('T','M','P') DEFAULT NULL,
  `active_from` date DEFAULT NULL,
  `active_till` date DEFAULT NULL,
  `always_active` enum('Y','N') DEFAULT NULL,
  `min_amount` float DEFAULT NULL,
  `status` enum('A','D') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_customers`
--

CREATE TABLE IF NOT EXISTS `jst_customers` (
  `id` int(11) NOT NULL,
  `card_id` varchar(20) DEFAULT NULL COMMENT 'External Card ID will be placed here',
  `fullname` varchar(100) DEFAULT NULL,
  `phnnumber` varchar(15) DEFAULT NULL,
  `altphnnumber` varchar(15) DEFAULT NULL,
  `tempaddress` varchar(150) DEFAULT NULL,
  `permanentaddress` varchar(150) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `subscribed_to_ad` enum('Y','N') DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_customers`
--

INSERT INTO `jst_customers` (`id`, `card_id`, `fullname`, `phnnumber`, `altphnnumber`, `tempaddress`, `permanentaddress`, `email`, `dateofbirth`, `remarks`, `subscribed_to_ad`, `created_on`) VALUES
(5, '43535', 'Debarati Bhattacharya', '07059772487', '07059772487', '72/1 Parnasree Pally, Behala Parnasree Park', '72/1 Parnasree Pally, Behala Parnasree Park', 'jhum.mou.90@gmail.com', '1990-05-05', 'NA', 'Y', '2016-01-24 10:11:20'),
(6, '54761', 'Snigdha Sen', '09830943383', '09830943383', '47 Amritlal Mukherjee Road', '47 Amritlal Mukherjee Road', 'samar.sen.1947@gmail.com', '1961-01-05', '', 'Y', '2016-01-24 13:24:17');

-- --------------------------------------------------------

--
-- Table structure for table `jst_old_ornaments`
--

CREATE TABLE IF NOT EXISTS `jst_old_ornaments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `actualmaker` varchar(100) DEFAULT NULL,
  `grossweight` float DEFAULT NULL COMMENT 'Gross weight in gm',
  `purity` float DEFAULT NULL COMMENT 'in Carat',
  `lossofweight` float DEFAULT NULL COMMENT 'In gm',
  `netweight` float DEFAULT NULL COMMENT 'in gm',
  `remarks` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_page_permission`
--

CREATE TABLE IF NOT EXISTS `jst_page_permission` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `page_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_page_permission`
--

INSERT INTO `jst_page_permission` (`id`, `user_id`, `user_type_id`, `page_name`) VALUES
(8, NULL, 1, 'addusertypes.php'),
(177, NULL, 1, 'addadvancesale.php'),
(178, NULL, 1, 'addcustomer.php'),
(179, NULL, 1, 'addpricingratetypes.php'),
(180, NULL, 1, 'addproduct.php'),
(181, NULL, 1, 'addproductcategory.php'),
(182, NULL, 1, 'addproductitems.php'),
(183, NULL, 1, 'addremovecart.php'),
(184, NULL, 1, 'addsale.php'),
(185, NULL, 1, 'addshop.php'),
(186, NULL, 1, 'adduser.php'),
(187, NULL, 1, 'checkout.php'),
(188, NULL, 1, 'dashboard.php'),
(189, NULL, 1, 'listadvancesales.php'),
(190, NULL, 1, 'listcustomers.php'),
(191, NULL, 1, 'listitems.php'),
(192, NULL, 1, 'listpricingratetypes.php'),
(193, NULL, 1, 'listproductcategories.php'),
(194, NULL, 1, 'listproducts.php'),
(195, NULL, 1, 'listshops.php'),
(196, NULL, 1, 'listusers.php'),
(197, NULL, 1, 'listusertypes.php'),
(198, NULL, 1, 'testdompdf.php'),
(199, NULL, 1, 'testing.php'),
(200, NULL, 1, 'users.php'),
(201, NULL, 1, 'vatsettings.php');

-- --------------------------------------------------------

--
-- Table structure for table `jst_partial_sale`
--

CREATE TABLE IF NOT EXISTS `jst_partial_sale` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `sale_item_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `to_type` enum('C','V') DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `amount_paid` float DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_pricing_rate_type`
--

CREATE TABLE IF NOT EXISTS `jst_pricing_rate_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(300) DEFAULT NULL,
  `type_value` float DEFAULT NULL,
  `status` enum('E','D') DEFAULT NULL COMMENT 'E for Enabled, D for Disabled ',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_pricing_rate_type`
--

INSERT INTO `jst_pricing_rate_type` (`id`, `type_name`, `type_value`, `status`, `created_on`, `updated_on`) VALUES
(1, 'Gold', 50, 'E', '2016-01-16 07:31:51', '2016-11-19 07:27:10'),
(2, 'Silver', 40, 'E', '2016-01-16 07:38:45', '2016-11-19 07:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `jst_product`
--

CREATE TABLE IF NOT EXISTS `jst_product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_product`
--

INSERT INTO `jst_product` (`id`, `name`, `description`, `category_id`, `createdon`) VALUES
(1, 'Baby Rings Type1', 'With Diamonds', 3, NULL),
(2, 'Silver Ring Type1', '', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jst_product_category`
--

CREATE TABLE IF NOT EXISTS `jst_product_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `making_charge` float DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_product_category`
--

INSERT INTO `jst_product_category` (`id`, `name`, `description`, `parent_id`, `making_charge`) VALUES
(1, 'Gold Ornaments', 'Gold made jewelries  ', 0, 12.5),
(2, 'Gold Ring', 'Different Type of Rings made of gold', 1, 10.75),
(3, 'Baby Rings', 'Small Size RIngs', 2, 0),
(4, 'Silver Ornaments', 'Silver Products', 0, 0),
(5, 'Silver Ring', 'Silver Ornament', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jst_product_item`
--

CREATE TABLE IF NOT EXISTS `jst_product_item` (
  `id` int(11) NOT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_image_url` varchar(200) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `uniqueid` varchar(40) DEFAULT NULL,
  `weight` float DEFAULT NULL COMMENT 'Weight of Product in gm',
  `purity` varchar(20) DEFAULT NULL COMMENT 'Purity in terms of carat',
  `pricewithoutmkchrg` float DEFAULT NULL,
  `makingcharge` float DEFAULT NULL COMMENT 'If not provided the making charge from product category will be considered',
  `pricing_rate_type_id` int(11) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_product_item`
--

INSERT INTO `jst_product_item` (`id`, `item_name`, `item_image_url`, `product_id`, `uniqueid`, `weight`, `purity`, `pricewithoutmkchrg`, `makingcharge`, `pricing_rate_type_id`, `createdon`) VALUES
(1, 'B Ring Itm2', NULL, 1, '21596', 10, '22', 5000, 0, 1, '2016-01-04 16:34:09'),
(2, 'B Ring Itm1', NULL, 1, '92757', 12, '12', 0, 0, 1, '2016-01-04 16:49:35'),
(3, 'Silver Wedding RIng', NULL, 2, '29775', 30, '', 0, 500, 2, '2016-05-07 12:56:47');

-- --------------------------------------------------------

--
-- Table structure for table `jst_purchase`
--

CREATE TABLE IF NOT EXISTS `jst_purchase` (
  `id` int(11) NOT NULL,
  `from_id` int(11) DEFAULT NULL,
  `fromtype` enum('C','V') DEFAULT NULL COMMENT 'C - Customer, V - Vendor',
  `item_id` int(11) DEFAULT NULL,
  `item_type` enum('OO','RM') NOT NULL COMMENT 'OO means old ornament, RM means raw material',
  `pricewithouttax` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `rateofmaterial` float DEFAULT NULL,
  `purchasedon` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_rawmaterial`
--

CREATE TABLE IF NOT EXISTS `jst_rawmaterial` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `stock` int(11) DEFAULT '0' COMMENT 'Item in stock (in gm)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_sales`
--

CREATE TABLE IF NOT EXISTS `jst_sales` (
  `id` int(11) NOT NULL,
  `to_id` int(11) DEFAULT NULL,
  `to_type` enum('C','V') NOT NULL,
  `sale_price` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `totalprice` float DEFAULT NULL,
  `coupon_code` varchar(20) DEFAULT NULL,
  `is_nonvat` enum('Y','N') DEFAULT NULL,
  `sold_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_sale_items`
--

CREATE TABLE IF NOT EXISTS `jst_sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `makingcharge` float DEFAULT NULL,
  `is_partial` enum('Y','N') DEFAULT 'N',
  `partial_id` int(11) DEFAULT NULL,
  `rateofmaterial` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_sale_item_price_explained`
--

CREATE TABLE IF NOT EXISTS `jst_sale_item_price_explained` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `sale_item_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `pricing_rate_type_id` int(11) DEFAULT NULL,
  `pricing_rate` float DEFAULT NULL,
  `fixedprice` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `making_charge_rate` float DEFAULT NULL,
  `discount_type` enum('F','P') DEFAULT NULL COMMENT 'F for Flat and P for percentage',
  `discount_value` float DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_scheme`
--

CREATE TABLE IF NOT EXISTS `jst_scheme` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `amount_interval` int(11) DEFAULT NULL,
  `reward_installments` int(11) DEFAULT NULL,
  `status` enum('A','D') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_scheme_taken`
--

CREATE TABLE IF NOT EXISTS `jst_scheme_taken` (
  `id` int(11) NOT NULL,
  `scheme_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `installments_paid` int(11) DEFAULT NULL,
  `next_installment_date` date DEFAULT NULL,
  `last_installment_paid_on` date DEFAULT NULL,
  `if_complete` enum('Y','N') DEFAULT NULL,
  `started_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_settings`
--

CREATE TABLE IF NOT EXISTS `jst_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `updated_on` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_settings`
--

INSERT INTO `jst_settings` (`id`, `name`, `value`, `updated_on`) VALUES
(1, 'VAT_PERCENTAGE', '13.52', '2016-01-16 16:48:31'),
(2, 'NOTIFY_ADMIN_DEPLETING_STOCK_THRESHOLD_IN_GM', '2', '2015-10-22 18:15:10'),
(3, 'GOLD_RATE', '100', '2015-10-22 18:15:34'),
(4, 'SILVER_RATE', '80', '2015-10-22 18:15:47'),
(5, 'VAT_NUMBER', '12346544411', '2016-01-16 16:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `jst_shop`
--

CREATE TABLE IF NOT EXISTS `jst_shop` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(400) DEFAULT NULL,
  `shop_address` varchar(400) DEFAULT NULL,
  `shop_phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_shop`
--

INSERT INTO `jst_shop` (`id`, `shop_name`, `shop_address`, `shop_phone`) VALUES
(1, 'S. B. Jewellers', '45, Chandicharan Ghosh Road, Barisha Silpara, Kolkata- 700 008', '033-2447 2096');

-- --------------------------------------------------------

--
-- Table structure for table `jst_temp_booked`
--

CREATE TABLE IF NOT EXISTS `jst_temp_booked` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_transaction_log`
--

CREATE TABLE IF NOT EXISTS `jst_transaction_log` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `partial_sale_id` int(11) DEFAULT NULL,
  `amount_paid` int(11) DEFAULT NULL,
  `type` enum('DEBIT','CREDIT') DEFAULT NULL,
  `transactiontime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jst_users`
--

CREATE TABLE IF NOT EXISTS `jst_users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `password` varchar(80) DEFAULT NULL,
  `phnnumber` varchar(15) DEFAULT NULL,
  `altphnnumber` varchar(15) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `position` varchar(20) DEFAULT NULL,
  `status` enum('A','D') DEFAULT NULL COMMENT 'A stands for Activated, D stands for Deactivated',
  `type` int(11) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_users`
--

INSERT INTO `jst_users` (`id`, `firstname`, `lastname`, `email`, `dateofbirth`, `password`, `phnnumber`, `altphnnumber`, `address`, `position`, `status`, `type`, `createdon`) VALUES
(1, 'Sutirtho', 'Sen', 'sutirtho.sen.90@gmail.com', '1990-09-22', '123456', '+919477082235', '', '47 Amritlal Mukherjee Road, Kolkata 700008', NULL, 'A', 1, '2015-10-25 09:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `jst_user_type`
--

CREATE TABLE IF NOT EXISTS `jst_user_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(200) DEFAULT NULL,
  `type_description` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jst_user_type`
--

INSERT INTO `jst_user_type` (`id`, `type_name`, `type_description`) VALUES
(1, 'Administrator', 'Administrator Account for jStoromatic');

-- --------------------------------------------------------

--
-- Table structure for table `jst_vendor`
--

CREATE TABLE IF NOT EXISTS `jst_vendor` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `ownername` varchar(100) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `phnnumber` varchar(15) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `remarks` varchar(800) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jst_advance_sale`
--
ALTER TABLE `jst_advance_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_advance_sale_items`
--
ALTER TABLE `jst_advance_sale_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_advertisement`
--
ALTER TABLE `jst_advertisement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_advertisment_broadcast_log`
--
ALTER TABLE `jst_advertisment_broadcast_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_coupon`
--
ALTER TABLE `jst_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_customers`
--
ALTER TABLE `jst_customers`
  ADD PRIMARY KEY (`id`) COMMENT 'customer_id';

--
-- Indexes for table `jst_old_ornaments`
--
ALTER TABLE `jst_old_ornaments`
  ADD PRIMARY KEY (`id`) COMMENT 'old_ornament_id';

--
-- Indexes for table `jst_page_permission`
--
ALTER TABLE `jst_page_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_partial_sale`
--
ALTER TABLE `jst_partial_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_pricing_rate_type`
--
ALTER TABLE `jst_pricing_rate_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_product`
--
ALTER TABLE `jst_product`
  ADD PRIMARY KEY (`id`) COMMENT 'product_id';

--
-- Indexes for table `jst_product_category`
--
ALTER TABLE `jst_product_category`
  ADD PRIMARY KEY (`id`) COMMENT 'product_category_id';

--
-- Indexes for table `jst_product_item`
--
ALTER TABLE `jst_product_item`
  ADD PRIMARY KEY (`id`) COMMENT 'item_id';

--
-- Indexes for table `jst_purchase`
--
ALTER TABLE `jst_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_rawmaterial`
--
ALTER TABLE `jst_rawmaterial`
  ADD PRIMARY KEY (`id`) COMMENT 'rm_id';

--
-- Indexes for table `jst_sales`
--
ALTER TABLE `jst_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_sale_items`
--
ALTER TABLE `jst_sale_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_sale_item_price_explained`
--
ALTER TABLE `jst_sale_item_price_explained`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_scheme`
--
ALTER TABLE `jst_scheme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_scheme_taken`
--
ALTER TABLE `jst_scheme_taken`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_settings`
--
ALTER TABLE `jst_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_shop`
--
ALTER TABLE `jst_shop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_temp_booked`
--
ALTER TABLE `jst_temp_booked`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_transaction_log`
--
ALTER TABLE `jst_transaction_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jst_users`
--
ALTER TABLE `jst_users`
  ADD PRIMARY KEY (`id`) COMMENT 'This is the user_id';

--
-- Indexes for table `jst_user_type`
--
ALTER TABLE `jst_user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jst_advance_sale`
--
ALTER TABLE `jst_advance_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `jst_advance_sale_items`
--
ALTER TABLE `jst_advance_sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `jst_advertisement`
--
ALTER TABLE `jst_advertisement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_advertisment_broadcast_log`
--
ALTER TABLE `jst_advertisment_broadcast_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_coupon`
--
ALTER TABLE `jst_coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_customers`
--
ALTER TABLE `jst_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `jst_old_ornaments`
--
ALTER TABLE `jst_old_ornaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_page_permission`
--
ALTER TABLE `jst_page_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=202;
--
-- AUTO_INCREMENT for table `jst_partial_sale`
--
ALTER TABLE `jst_partial_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_pricing_rate_type`
--
ALTER TABLE `jst_pricing_rate_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `jst_product`
--
ALTER TABLE `jst_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `jst_product_category`
--
ALTER TABLE `jst_product_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `jst_product_item`
--
ALTER TABLE `jst_product_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `jst_purchase`
--
ALTER TABLE `jst_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_rawmaterial`
--
ALTER TABLE `jst_rawmaterial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_sales`
--
ALTER TABLE `jst_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_sale_items`
--
ALTER TABLE `jst_sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_sale_item_price_explained`
--
ALTER TABLE `jst_sale_item_price_explained`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_scheme`
--
ALTER TABLE `jst_scheme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_scheme_taken`
--
ALTER TABLE `jst_scheme_taken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_settings`
--
ALTER TABLE `jst_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `jst_shop`
--
ALTER TABLE `jst_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jst_temp_booked`
--
ALTER TABLE `jst_temp_booked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_transaction_log`
--
ALTER TABLE `jst_transaction_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jst_users`
--
ALTER TABLE `jst_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jst_user_type`
--
ALTER TABLE `jst_user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
