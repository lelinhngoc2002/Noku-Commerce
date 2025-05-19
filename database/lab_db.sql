-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 05:07 AM
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
-- Database: `lab_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `user_name`, `order_date`, `total`) VALUES
(26, 15, 'Vũ Trung Nghĩa', '2025-05-16 09:56:22', 2029000.00),
(27, 9, 'Mỹ Hạnh', '2025-05-16 10:21:57', 2165000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `user_name`, `quantity`, `price`) VALUES
(42, 26, 1, 'Sữa rửa mặt Dr.Ceutics (Màu ngẫu nhiên)', 'Vũ Trung Nghĩa', 1, 128000.00),
(43, 26, 2, 'Sữa rửa mặt Loreal', 'Vũ Trung Nghĩa', 1, 200000.00),
(44, 26, 3, 'Sữa rửa mặt Loreal Paris', 'Vũ Trung Nghĩa', 1, 300000.00),
(45, 26, 4, 'Kem dưỡng ẩm Cerave', 'Vũ Trung Nghĩa', 1, 267000.00),
(46, 26, 5, 'Son dưỡng Dior', 'Vũ Trung Nghĩa', 1, 1000000.00),
(47, 26, 6, 'Kem chống nắng Vaseline', 'Vũ Trung Nghĩa', 1, 134000.00),
(48, 27, 15, 'Phấn trang điểm Thorakao', 'Mỹ Hạnh', 1, 80000.00),
(49, 27, 16, 'Serum vitamin C Daytox', 'Mỹ Hạnh', 1, 168000.00),
(50, 27, 17, 'Serum L\'Oreal Hyaluronic Acid Cấp Ẩm Sáng Da 30ml Revitalift Hyaluronic Acid 1.5% Hyaluron Serum', 'Mỹ Hạnh', 1, 305000.00),
(51, 27, 18, 'Chanel Xịt Khử Mùi Hương Nước Hoa Chanel Coco Mademoiselle Deodorant Vaporisateur Spray 100ml', 'Mỹ Hạnh', 1, 1500000.00),
(52, 27, 20, 'Tẩy Da Chết Mặt Cocoon Cà Phê Đắk Lắk 150ml Dak Lak Coffee Face Polish', 'Mỹ Hạnh', 1, 112000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `created_at`, `updated_at`, `image`) VALUES
(1, 'Sữa rửa mặt Dr.Ceutics (Màu ngẫu nhiên)', 128000.00, 'Sữa rửa mặt Dr.Ceutics sạch sâu, ngừa mụn, phù hợp với mọi loại da.', '2025-05-06 07:40:47', '2025-05-08 07:50:07', '681c5eec4b073_srm.jpg'),
(2, 'Sữa rửa mặt Loreal', 200000.00, 'Làm sạch sâu lỗ chân lông, loại bỏ bụi bẩn, dầu thừa và tế bào chết tích tụ trên bề mặt da.', '2025-05-06 07:40:47', '2025-05-08 08:28:36', '681c6b3412543_loreal.jpg'),
(3, 'Sữa rửa mặt Loreal Paris', 300000.00, 'Chống lão hóa, xóa nếp nhăn', '2025-05-06 07:40:47', '2025-05-08 08:30:48', '681c6bb86d8b8_Revitalift.jpg'),
(4, 'Kem dưỡng ẩm Cerave', 267000.00, 'Dưỡng ẩm, làm mềm, dịu da bị kích ứng, đỏ rát.', '2025-05-08 08:15:29', '2025-05-08 08:34:16', '681c6c880a4d0_Cerave.jpg'),
(5, 'Son dưỡng Dior', 1000000.00, 'Mùi hương dịu nhẹ, quyến rũ, tạo cảm giác dễ chịu khi sử dụng.', '2025-05-08 08:15:49', '2025-05-09 03:32:31', '681c6d2148d5d_SonDior.png'),
(6, 'Kem chống nắng Vaseline', 134000.00, 'Giúp chống nắng hiệu quả', '2025-05-08 08:15:57', '2025-05-08 08:39:04', '681c6da867183_Vaseline.jpg'),
(7, 'Kem chống nắng D\'alba', 487000.00, 'Phù hợp với mọi loại da, đặc biệt là da nhạy cảm, với thành phần lành tính giúp làm dịu và cung cấp độ ẩm dồi dào. ', '2025-05-08 08:16:04', '2025-05-08 08:40:59', '681c6e1b7db49_D\'alba.jpg'),
(8, 'Son môi YSL 407 (Hồng Đất)', 1350000.00, 'Son YSL nổi bật với chất son lì, mềm mịn và độ bền màu cao, mang lại cảm giác thoải mái khi sử dụng. ', '2025-05-08 08:16:08', '2025-05-14 07:17:31', '681c6ea4e067b_YSL.png'),
(9, 'Son môi Tom Ford', 1250000.00, 'Giữ ẩm tốt, độ bám màu cao.', '2025-05-08 08:16:12', '2025-05-12 08:20:17', '681c6eed091d3_TFord.jpg'),
(12, 'Dung dịch tẩy trang Loreal Micellar 3 trong 1', 167000.00, 'Làm sạch sâu các lớp makeup, kem chống nắng và giúp thông thoáng lỗ chân lông.\r\nDưỡng ẩm làn da.\r\nBổ sung dưỡng chất giữ ẩm, dưỡng mềm da sử dụng được cho cả mắt, mặt và môi.', '2025-05-12 03:16:18', '2025-05-12 03:16:18', '6821680231deb_Loreal_Micellar.jpg'),
(13, 'Kem chống nắng Sunplay', 126000.00, 'Chống nắng cực mạnh với SPF81, PA++++ giúp chống tia UVA/UVB tối đa, ngăn đen sạm, nám, lão hóa sớm, bảo vệ da không bị bỏng rát, cháy nắng trong nhiều giờ.\r\nMàng chắn UV Shield thế hệ mới với kết cấu độc đáo phủ đều trên da, chống nắng hiệu quả; đồng thời mang lại cảm giác thoáng mịn, không gây khô da.\r\nVitamin C, E, Pro Vitamin B5 & Hyaluronic Acid giúp giữ ẩm và nuôi dưỡng da.\r\nKhả năng kháng nước và mồ hôi cao. Công thức đặc chế cho vận động liên tục ngoài trời hoặc dưới nước.', '2025-05-13 08:48:32', '2025-05-13 08:48:32', '682307602bdf5_Sunplay.jpg'),
(14, 'Mặt Nạ Bio-essence Vitamin B5 Dưỡng Ẩm, Phục Hồi Da 20ml', 25000.00, 'Chứa đến 99% Vitamin B5 nguyên chất khoá ẩm giúp làn da luôn tươi trẻ, mịn màng, làm dịu các vết ửng đỏ trên da.\r\n\r\nMặt nạ sợi độc quyền từ Đức với công nghệ Hydrosorb giữ lại công thức tinh chất dưỡng ẩm cao và thẩm thấu sâu vào bên trong da.\r\n\r\nKhả năng bám dính cao gấp 2 lần sản phẩm thông thường giúp các hoạt chất thẩm thấu vào da hiệu quả.\r\n\r\nCác sợi nano siêu mỏng để giữ tinh chất tối đa tăng cường khả năng dưỡng ẩm gấp 2 lần.\r\n\r\nNgăn vi khuẩn, bảo vệ cho làn da nhạy cảm, dễ bị mụn trứng cá gấp 5 lần.', '2025-05-14 07:19:40', '2025-05-14 07:19:40', '6824440c1cc77_bio_essence.jpg'),
(15, 'Phấn trang điểm Thorakao', 80000.00, 'Phấn trang điểm trắng da Thorakao Two Way Cake 9g được chiết xuất từ thiên nhiên giúp da trắng sáng ,thấm hút lượng dầu thừa trên bề mặt da, tạo lớp nền mỏng mịn, giữ độ ẩm .', '2025-05-14 07:22:03', '2025-05-14 07:22:03', '6824449b5267f_Thorakao.jpg'),
(16, 'Serum vitamin C Daytox', 168000.00, 'Giúp chống oxy hóa, giúp làm sáng da, mờ thâm nám và đều màu da hiệu quả. Sản phẩm hỗ trợ kích thích sản sinh collagen, cải thiện độ đàn hồi và mang lại làn da rạng rỡ, khỏe mạnh.', '2025-05-14 07:27:24', '2025-05-14 07:27:24', '682445dc8dc64_serum_vitaminC.jpg'),
(17, 'Serum L\'Oreal Hyaluronic Acid Cấp Ẩm Sáng Da 30ml Revitalift Hyaluronic Acid 1.5% Hyaluron Serum', 305000.00, 'Tinh Chất Cấp Ẩm Sáng Da L\'Oreal Paris Revitalift Hyaluronic Acid 1.5% Hyaluron Serum là dòng sản phẩm serum đến từ thương hiệu L\'Oreal Paris nổi tiếng của Pháp, với sự kết hợp không chỉ 1 mà đến 2 loại Hyaluronic Acid ưu việt ở nồng độ 1.5% sẽ là giải pháp chăm sóc da hiệu quả dành cho làn da khô & lão hóa, giúp cung cấp độ ẩm tối đa cho da căng mịn và tươi sáng tức thì. Với Revitalift HA đậm đặc, làn da sẽ có sự thay đổi rõ rệt, mang đến vẻ ngoài rạng rỡ cho bạn.', '2025-05-14 07:29:30', '2025-05-14 07:29:30', '6824465aa02de_serum_Loreal.jpg'),
(18, 'Chanel Xịt Khử Mùi Hương Nước Hoa Chanel Coco Mademoiselle Deodorant Vaporisateur Spray 100ml', 1500000.00, 'Xịt Khử Mùi Hương Nước Hoa Chanel Coco Mademoiselle Deodorant Vaporisateur Spray 100ml là chai xịt khử mùi nước hoa hỗ trợ cho làn da dưới cánh tay của cơ thể bạn luôn tươi mát và khô ráo. Chanel Coco Mademoiselle mang lại cho nữ giới một mùi hương tinh khiết, ngọt ngào, gợi cảm, hỗ trợ làm giảm mùi hôi ở trên cơ thể.', '2025-05-14 07:35:34', '2025-05-14 07:36:44', '6824480c5f494_Chanel White.jpg'),
(19, 'Kem dưỡng ẩm Olay', 168000.00, 'OLAY Luminous là dòng sản phẩm dưỡng sáng da, làm mờ thâm nám mới đến từ thương hiệu mỹ phẩm OLAY, được cải tiến từ phiên bản cũ White Radiance đã vô cùng thành công trước đó. Với thành phần chính là Niacinamide (Vitamin B3), OLAY Luminous sẽ giúp nuôi dưỡng và cải thiện tình trạng da xỉn màu hiệu quả, cho làn da toả sáng rạng rỡ chỉ trong 28 ngày.', '2025-05-14 07:40:18', '2025-05-14 07:40:18', '682448e291367_Olay.jpg'),
(20, 'Tẩy Da Chết Mặt Cocoon Cà Phê Đắk Lắk 150ml Dak Lak Coffee Face Polish', 112000.00, 'Tẩy Da Chết Mặt Cocoon Cà Phê Đắk Lắk 150ml là dòng sản phẩm tẩy tế bào chết da mặt đến từ thương hiệu mỹ phẩm Cocoon Việt Nam. Thành phần được làm từ những hạt cà phê Đắk Lắk xay nhuyễn giàu cafeine hòa quyện với bơ cacao Tiền Giang giúp bạn loại bỏ lớp tế bào chết già cỗi và xỉn màu, đánh thức làn da tươi mới đầy năng lượng cùng cảm giác mượt mà và mềm mịn lan tỏa.', '2025-05-14 07:51:12', '2025-05-15 08:56:40', '68244b6feeb38_Cocoon.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(2, 'Lê Ngọc Linh', 'linh@gmail.com', '$2y$10$aUhzehDQRyaH80HufKhYne4153BKDb2OGjTgse98KJ9gvanLzOCoq', 'admin', '2025-05-07 04:27:25', '2025-05-19 01:46:51'),
(3, 'Nhựt', 'nhut@gmail.com', '$2y$10$EwLO7.MGTQ1nVh62PY0JhOQvbCfbYhiltGwx.iVrwW3jU08OhGkwu', 'admin', '2025-05-07 04:53:20', '2025-05-07 04:53:20'),
(6, 'Khoa', 'khoa@gmail.com', '$2y$10$znwXRwH0datflZVLKQfAmODbQMxabAnvl2xWUiM1UlRlsM19A7FjG', 'admin', '2025-05-07 08:52:13', '2025-05-07 08:52:13'),
(7, 'Quang Anh', 'quanh@gmail.com', '$2y$10$GiZoCQYB1Hgy0GMX22dhSev5An4VdVMmsIEH99mIB41nrAnNDRLfS', 'admin', '2025-05-07 09:26:05', '2025-05-07 09:26:05'),
(8, 'Nam', 'thethoi@gmail.com', '$2y$10$i6zCWr/UiTQg5YcmuMplmOhZDE4c4gTqO8DQlptPqdxskvMPyZk6C', 'admin', '2025-05-07 10:32:20', '2025-05-07 10:32:20'),
(9, 'Mỹ Hạnh', 'myhanh@gmail.com', '$2y$10$CBjFU5r7ZeUXrX/yWV3i6eFWmYgTaSpEKLVy0JsWbD0LPW8h7Y/VS', 'member', '2025-05-08 09:50:22', '2025-05-16 08:59:33'),
(10, 'Thanh Ba', 'baga@gmail.com', '$2y$10$AT7ze1tGubTElaCs3ELTiunAxXkUO.9CvdljZr7W5i7ksS7Xu0UtG', 'admin', '2025-05-12 08:21:06', '2025-05-12 08:21:06'),
(11, 'Tam Gandr', 'tamgandr@gmail.com', '$2y$10$cIuLkEgOFYcLwPwgXkP2mO54SJ/hmUUZYU.8HJa/3EcS73HpSjjQa', 'member', '2025-05-12 08:21:22', '2025-05-19 01:47:07'),
(12, 'Đỗ Văn Tiến', 'tiendo@gmail.com', '$2y$10$ID01SQdy5koc942HyihFT.tkQBNt8gtlZ2Je5YJIh3Pbmkzf1FNHq', 'admin', '2025-05-13 08:09:42', '2025-05-13 08:09:42'),
(13, 'Đặng Hoàng Quân', 'quanthr@gmail.com', '$2y$10$dbsyBih1ZYxMRZZ1/ysiMu2wIospNjXIB8eIC5zsrJmOV9vIc2SQW', 'member', '2025-05-15 08:42:51', '2025-05-15 08:50:48'),
(14, 'Huỳnh Thanh Gia Kiệt', 'giakiet@gmail.com', '$2y$10$3cTpyBGtvwMo6391JZU3.er6ryaWqRBdwdDdoM6JyFznr.60l5DfW', 'member', '2025-05-15 08:44:58', '2025-05-19 01:47:00'),
(15, 'Vũ Trung Nghĩa', 'vunghia@gmail.com', '$2y$10$vmeK6txyjO1CXnvwsGzCKO3Qi9.WFNn7jUUQCIIW7bW39WNp3oRNS', 'member', '2025-05-15 08:55:18', '2025-05-19 02:23:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_ibfk_1` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
