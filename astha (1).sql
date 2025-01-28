-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 28, 2025 at 01:42 AM
-- Server version: 8.0.40-0ubuntu0.22.04.1
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `astha`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_restore`
--

CREATE TABLE `customer_restore` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `email`, `phone`, `created_at`) VALUES
(8, 'Rana', 'nobody013579@gmail.com', '1805661664', '2025-01-28 01:36:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `price`, `quantity`, `amount`) VALUES
(8, 8, 'Super Oil', 350.00, 1, 350.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Super Oil', 'sds', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `variation_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `picture` longblob,
  `is_visible` tinyint(1) DEFAULT '1',
  `expiration_date` date DEFAULT NULL,
  `picture_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `product_id`, `variation_name`, `price`, `picture`, `is_visible`, `expiration_date`, `picture_type`) VALUES
(1, 1, '2L', 350.00, 0xffd8ffe000104a46494600010100000100010000ffdb00840009060710131210121212131512171712181216161812151716151a11171615151718191d2820181b251b151621312125292b2e2e2e17203338332c37282d2e2b010a0a0a0e0d0e1b10101b2d25201f2f312d2f2f312f2d2e2d2d372d2d2b2f302b2d2b2f382d2d2d2d2d2d2b2f2d2d2d2f2d2d2d2d2b2d2d2d2b2d2d2b2d2d352dffc000110800e100e103012200021101031101ffc4001b00010002030101000000000000000000000004060203050107ffc4003b10000201020402070605020603000000000001020311041221314151050661718191b1132232a1c1d1425292e1f01453152362b2c2f13382a2ffc4001a010100030101010000000000000000000000020304010506ffc4002d11010001030302060005050000000000000001020311041221314105132232516115335291a162b1c1e1f0ffda000c03010002110311003f00fb880000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001e33d3c60417d2f4149c1d48a92d357657e366f464d8cd3d56c56fa67abede6952b4936dca94b66dbbb709707d8f4ee38f84c0e2e9d9d0a9284b7546a5d5d7151bfbafbbe67933add45ab9b6edbe3b4c7fdd5b22c5bae9cd3573f6be39119f4951fee43cd1c35d3ce54aac2b41c2ae592b736d5bc0e0c23a7132ebbc6e2d622d444e7ae7b7d61658d0cd59dfc7f95e574a50fee43cc934aaa924e2d34f66b547cf5c1b5a265a3aa3517b171beb194aebb1bba25e1be2f5eaaef975c4470e6a7474daa3744e5dd001ef30000000000000000000000000000000000000000000003cb1ab118784d6592baf47cd7266e3c672a8898c48a675a7111865a4e69c95a5194af78c6ff000c9a5adfe872e15e0d695a95f95daf547bd6cc3d3ab5dcb0f38ca5b547f8737649e8df34b6393fe0f574bfb27df289f23add2555deab8cc76eef7f4d36fca8cd5897552ab6796507dd2fd8e8f532bce35aa46a472e64b2bcd169d9f67795c7d1155f0a37e6a4b4f225f43613d9d6a72af57242328bbc5b92ba69da57f8569bf0ec23a2d3ddb57a9aa2935136eab7546e7d38f4c29c9349a69a7b35b35ccccfb178000000000000000000000000000000000000000000063295b57b018d6ab18a72934a2936dbd125cd948e96eb0cf12dd3a09aa3b4a7b67e767bdbb178be06beb474b2af3f671bca947f02fc72e72e4970b91b0ded38a8c5704b5f9b56f91e3eab59355534513c7cb4dbb588dd2d94706925a68bc8f5a7b462bbdfdb89328d0bacd2f37f77e86aa924b6679f55bc46576e6b74e3f9579234e2e9597c177c15bf9625c2b35b597af9fdac3d9c9eba9ddb131c39979d0fd27530d64fdea5c62bf0f370e5dde85d7078b8558a9c2578bf5e29ae0ca0e25b57496bf2f13674363a7869df784aded17fc9769bb4bab9a2628aba7f6535dbcf30fa0835d1ab192528b4d3d9a361ecb38000000000000000000000000000000000000577ae38c71842945d9d46eef8e55ba5deda2c4533aecffcfc2f75428d54cc5aab09dbf74399430d08ab256fe6ec9d4a8a595daedfc31dfc59a29f713238a4b5b6b6b2eee47836f6e79699cb6ac1f1a936df2bedd97e1e1623d5ab15a452ef35d7aee5b9a8957769e94c39113dd9c2a34ee4cf6d9a2945a4deff00ce473cf510a2eede1d98ca7cfa315aea579792f0399569f068994b1525da6bc454ccef6275cdb98cd3c3919ee95d4cc4b8d4ad45b7965efc1727b4d2efd1f7df9971285d0f3cb8aa36e2da7dce2cbe9eb686a9aad73d945d8f500036ab000000000000000000000000000000000a875ea9a52c3d477baceacbb96ddba96f385d6ba5074e0dbf7a334e0ad7be9aaecd35bf6155ea77513095338953e8f49c2df0565df4ea2fa1bbfae83fee7e897d8cb198eab0af86a714b24dcb3dd36f4b5b2b4ecb9ea9f81d4cc79f3e1f1daa5d35cc627e5c9fea63febfd323255a3feaf26757305223f877f57f0e79b2e5aa8b94bc99ecaac56f9bc99d4b91f1f5e70a729416692d93bf3473f0d9fd5fc1e6ca03c7535f9ff4c8d353a521c2353f44fec751e2ed084e7eedf2df945be6f95cf238da6da4aa45b7b2524c946823f51e64fc23f5757b5c45097bc92cd2b34d3d135669ec7d04a8f57f0d7c54a6e5f04748fe6bad5f87d4b71b34946ca263ee55dc9cc800352000000000000000000000000000000000159ebbd3938e1da4da5535e4aeb4bf669e8598ae75c710e30a51e1293beb6dadf72bbd8d9394a8eae2c6716d3f7aeb6f897ecfc492a5fcd0854669371d777c6febb1ba9c1666edae9ea4b6b88b88c43a129ca72bd2796dbb7179acfbee9a4bb91d0a7524e9e6cb79656f2abeaed7b26d2eed8d58fc27b48e5527196f192b37196569495f8abdd76a4517a4ba2f17469b729ca14d5585393552a39e213aaad524b3b50ef4eeefc0a222a8aa67b36e974f45d8c4d589cbadd1fd64c4b8fb4a90a7abf768c6188f6b979a928ca2df63b6dab459f078a8d5829c7325c54938ca2f8c6517b347cff1b858c25888c1d5bc71b1a74e119d5729c1c2529423ef5af7b36df2dcb275270b150ad3cc9d4752719a5394fd9dad6a72bb6b32b6eafbeec9532d7add3da8b7be9e1dcc546328b53578e8decf66a4b4e3aa5a1030d82a1095e374f47adeda6ab5648c75377bdb4b2d786dcc8d1a2dbb69f2fe712ddb0f222a98e1d5e84d7191b27ff8e4dbe1c122e454babf55c712e36569c15df1f7569af22da556223138f992a0005e8800000000000000000000000000000000573aeb18fb283fc49c9c5dedf81a7ea8b195aeba516e14df04e69f8c2ebfda537e716e52a3dd0e361e9b777a715af136c9db373d3d4ad51c5b8453bcb5e57d3b4ecd2a8deedf99e4d5e2f1b31b6632b7c99ea9b4eb48e1f4ce0717889ba578c694674e716d45c5a8a4d2924f3b939a7c95b8b3b10647960682cd2705cdbbcbee67d3eb6ac7aa6565154dbab34c72d14ba121515575e8a84aa54555e4a952525512b678cd6571d364bc7b26e0b070a1171a69a4db949b729ca527bca52936dbd17911e951a1295f27bda3bfbebebd8fc8df1a518df2ab5f57bbf52dbdabc4712e5572bab899e3e0aca4da6dd976e8affcb1add2bfe25e52b79e532a8e3bbe1d9dbcee479e321c2efb9afdcdd675f67cb8dd573853344e5d7ea9414abd59ca5efc73c62b4bb8e6f45f52e451ba9b795794b2e96a8dbe0aed697f12f25ba1ab75acfdcb973a80036200000000000000000000000000000000015beb84de58256daa3d7b97dcb2155eb7279e16b6b0925d9ad9faa33eae716aa4edfba145a13834aeeebf9b9d4a5888e9afa9a21d0d24b8797fd9223d113bdf3c573d6df63e56ab533f2d2954eb27c4dca447861251e5fa93fa9959a33ecae99e20c2374557a0dd4f64a49fbae599495d3cca396ff856569226cea1cee8ee8ff64e6d6ae596efdfd95ecb56f4599ec4b7466f979965e89aeaf4e70ecd34c4fa7a236324de5b45cb5b36b4b2e644c451e514ddf8b7f0e9776e674eb74639c1c2791abc65f135ac6f6d9769aa5d1997e18c2f66af79bdd59ef63758b16a2de6b99cfc6109ce7875ba8b564a728e995e66fbd2562ee513aad4674eb538dd59b95fc63fb17b3daf0fcf93113d94ddf7000372b000000000000000000000000000000000abf5b29da74e6e492cad76ab3bdfbb5f91682a7d6fa69d4a777f876ee97ee66d5fe54acb5ee70e96229bda6ea3e0a2ade6d6c4b9a93b5ddbb15fd789ab0f04be1f52438be6781b271cb54cf2d0e9cbf379e6fa330f6b97e26dbe518c9fdcdb28dbf1fa184ea28fc5293ee8b2bd98772f238893da0fc6cbeacd8abbd9af937f348d7fd57e58b63db54e518f7bd4e67061229cefb5fc9af515eac62b5763185f8b42693d5d9f91751138ea8cb6f41e678aa6dbd35b2ffd5978295d07597f534d6ff1783caf7f0f52ea7b3e1f8f2e71f2cf7ba800372a000000000000000000000000000000000a9f5b695ea5392fcbafea767ea5b0ae75aa0d3a534da4d4a32f9357f2666d5e7c99c2cb5ee857a59de97cab8db77e3c0ca9d34bf7b333870bbdf6d97c919c9db7bf82d0f9fa6b896b9a65adc3b65e1a11a54a9abfbceeb7d5dfe7a1b2a63126924afdb75f4354b139926a11927c5ed75c2cc57312ec44bd9d44945c54ea5ddaca57b76bd6c912d4f92b78105e32715ad28a7c166bf76db1269d69351f756bbd9decf86f6b918988e249897b3a777ab6799177ff00399d0a7460f56dff00f1f7669745ddbbdd2b5d5ade7b9d9db1d21ce5b3ab987ff3e16e199bed795e9f32ea547a09df1314959463294b7ee5ebf32dc7bba0fcae98e596f7b8001b5500000000000000000000000000000000046e90c22ab0941f1d9f27c19241c98898c491c3e7d88a53a126aa41b95ec9ef1ec77e4725e3abd45794b2aec4925e3bbf923ea9529c5ab349aedd4815ba0b0d2de92f0baf43c6b9e1b5e7d13186ba7511de140861e9a8d39b79a599fbef5eeec32ad5a3952b691ccf44a3793da5ddbf1e65f29f40e1e2aca3a6d6bdf4e5a90eb754b0b2dd4eda2b293b596cbe6ca7f0ebff005fba5e7d1f6a0e1711272bb6af76d5ddeeed65af672ed257f599d3cf1bb83b5925a49fec5ca8f53b071da32f1937ea49c3f56b0b0939aa7ef3d5b6dbbbe7de77f0ebd3f1fbff00a26fd0a7d3c4249a4ad34949dac9a77b5b5d3cfea4de8dc64a4fe1bdd35649ebaf2fe2ee2dbfe1342f7f669be37bb24d2a108fc314bb92254785ddcfaaa888467514e38873ba07a37d92949af7e6f5beb65c11d600f6add1145314c7665aa73390004dc00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000007ffd9, 1, '2025-02-14', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `supplier_id`, `product_name`, `product_price`, `quantity`, `order_date`) VALUES
(1, 2, 'Chips', 500.00, 50, '2025-01-28 06:12:27'),
(2, 2, 'Teer', 800.00, 4, '2025-01-28 06:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `email`, `created_at`) VALUES
(2, 'Rana', '1805661664', 'habibur.rahman5577@gmail.com', '2025-01-27 17:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_restore`
--

CREATE TABLE `supplier_restore` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int NOT NULL,
  `unit_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_name`) VALUES
(2, 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `unit_restore`
--

CREATE TABLE `unit_restore` (
  `id` int NOT NULL,
  `unit_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customer_restore`
--
ALTER TABLE `customer_restore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `supplier_restore`
--
ALTER TABLE `supplier_restore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_restore`
--
ALTER TABLE `unit_restore`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_restore`
--
ALTER TABLE `customer_restore`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_variations`
--
ALTER TABLE `product_variations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier_restore`
--
ALTER TABLE `supplier_restore`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unit_restore`
--
ALTER TABLE `unit_restore`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD CONSTRAINT `product_variations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
