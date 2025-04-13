-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 30, 2025 lúc 09:14 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `beauty_clinic`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointments`
--

CREATE TABLE `appointments` (
  `id_appointment` bigint(20) UNSIGNED NOT NULL,
  `id_patient` bigint(20) UNSIGNED DEFAULT NULL,
  `id_doctor` bigint(20) UNSIGNED NOT NULL,
  `id_service` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_email` varchar(100) DEFAULT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `appointment_time` datetime NOT NULL,
  `status` enum('scheduled','completed','cancelled','no-show') NOT NULL DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `appointments`
--

INSERT INTO `appointments` (`id_appointment`, `id_patient`, `id_doctor`, `id_service`, `guest_name`, `guest_email`, `guest_phone`, `appointment_time`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 3, 3, 'kaka', 'ka@gmail.com', '0123456789', '2025-04-18 11:11:00', 'scheduled', 'nứng', '2025-03-30 05:07:20', '2025-03-30 05:07:20'),
(2, 2, 3, 7, NULL, NULL, NULL, '2025-04-17 17:05:00', 'scheduled', 'ổn đầy bìa', '2025-03-30 05:08:29', '2025-03-30 05:08:29'),
(3, 2, 5, 5, NULL, NULL, NULL, '2025-04-05 19:24:00', 'scheduled', 'cừi ia', '2025-03-30 05:19:24', '2025-03-30 05:19:24'),
(4, NULL, 3, 6, 'huy', 'huy@gmail.com', '0123456789', '2025-04-19 07:26:00', 'scheduled', 'bị ỉa chảy', '2025-03-30 08:23:30', '2025-03-30 08:23:30'),
(5, 7, 4, 5, NULL, NULL, NULL, '2025-04-25 15:52:00', 'scheduled', 'kkkkkk', '2025-03-30 08:25:18', '2025-03-30 08:25:18'),
(6, 7, 4, 4, NULL, NULL, NULL, '2025-04-15 15:31:00', 'scheduled', 'ggggggg', '2025-03-30 08:26:16', '2025-03-30 08:26:16'),
(7, 2, 6, 3, NULL, NULL, NULL, '2025-04-12 15:32:00', 'scheduled', 'fffff', '2025-03-30 08:27:40', '2025-03-30 08:27:40'),
(8, 7, 5, 3, NULL, NULL, NULL, '2025-04-12 00:36:00', 'scheduled', 'hế nhô', '2025-03-30 10:32:29', '2025-03-30 10:32:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id_category` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id_category`, `name`, `created_at`, `updated_at`) VALUES
(1, 'La Beauty', '2025-03-16 17:51:48', '2025-03-16 17:51:48'),
(2, 'Fixderma', '2025-03-16 17:51:48', '2025-03-16 17:51:48'),
(3, 'GAMMA', '2025-03-16 17:51:48', '2025-03-16 17:51:48'),
(4, 'On: The Body', '2025-03-16 17:51:48', '2025-03-16 17:51:48'),
(5, 'Laroche posay', '2025-03-16 17:51:48', '2025-03-16 17:51:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cosmetics`
--

CREATE TABLE `cosmetics` (
  `id_cosmetic` bigint(20) UNSIGNED NOT NULL,
  `id_category` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` double(8,2) NOT NULL DEFAULT 0.00,
  `isHidden` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cosmetics`
--

INSERT INTO `cosmetics` (`id_cosmetic`, `id_category`, `name`, `price`, `rating`, `isHidden`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sữa tắm gội em bé Gentle Wash 500ml La Beauty', 159000.00, 5.00, 0, 'user/theme/images/products/la1.jpg', '2025-03-16 17:56:04', '2025-03-30 11:25:54'),
(2, 2, 'Gel chấm mụn, mờ thâm Decumar Advance THC 20g', 105000.00, 4.00, 0, 'user/theme/images/products/fix1.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(3, 3, 'Dung dịch vệ sinh vùng kín Bimunica 250ml', 184000.00, 4.80, 0, 'user/theme/images/products/gam1.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(4, 1, 'Dầu Dừa Tươi Raw Virgin Coconut Oil Coboté 50ml', 81000.00, 4.20, 0, 'user/theme/images/products/la2.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(5, 2, 'Kem giảm thâm vùng nách, mông, bikini Neotherica Armpil Cream', 139000.00, 3.90, 0, 'user/theme/images/products/fix2.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(6, 3, 'Gel rửa mặt SVR Sebiaclear Gel Moussant 200ml', 390000.00, 4.70, 0, 'user/theme/images/products/gam2.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(7, 4, 'Bọt vệ sinh nam giới Sumely Mens Sanitary Foam', 96000.00, 4.10, 0, 'user/theme/images/products/decu1.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(8, 4, 'Sữa rửa mặt On: The Body Rice Therapy Heartleaf Acne Cleanser', 165000.00, 4.30, 0, 'user/theme/images/products/decu2.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(9, 5, 'Kem chống nắng La Roche-Posay Anthelios UVMune 400 SPF50+', 450000.00, 4.90, 0, 'user/theme/images/products/laro1.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(10, 5, 'Nước tẩy trang La Roche-Posay Micellar Water Ultra', 395000.00, 4.60, 0, 'user/theme/images/products/laro2.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04'),
(11, 1, 'Mặt nạ dưỡng ẩm La Beauty Hydrating Mask', 75000.00, 2.00, 0, 'user/theme/images/products/la3.jpg', '2025-03-16 17:56:04', '2025-03-30 12:09:10'),
(12, 2, 'Serum trị mụn Fixderma Acne Clear Solution', 220000.00, 4.40, 0, 'user/theme/images/products/fix3.jpg', '2025-03-16 17:56:04', '2025-03-16 17:56:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory`
--

CREATE TABLE `inventory` (
  `id_inventory` bigint(20) UNSIGNED NOT NULL,
  `id_cosmetic` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `supplier` varchar(100) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `medical_records`
--

CREATE TABLE `medical_records` (
  `id_medical_record` bigint(20) UNSIGNED NOT NULL,
  `id_patient` bigint(20) UNSIGNED NOT NULL,
  `id_doctor` bigint(20) UNSIGNED NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `pdf_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `medicines`
--

CREATE TABLE `medicines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `manufacturer` varchar(255) NOT NULL,
  `expiry_date` date NOT NULL,
  `dosage_form` varchar(255) NOT NULL,
  `usage_instructions` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2024_03_07_000001_create_medicines_table', 1),
(4, '2024_03_07_000003_create_treatments_table', 1),
(5, '2024_03_10_000001_create_users_table', 1),
(6, '2025_02_27_000001_create_roles_table', 1),
(7, '2025_02_27_000002_create_ranks_table', 1),
(8, '2025_02_27_000004_create_categories_table', 1),
(9, '2025_02_27_000005_create_cosmetics_table', 1),
(10, '2025_02_27_000006_create_inventory_table', 1),
(11, '2025_02_27_000007_create_medical_records_table', 1),
(12, '2025_02_27_000008_create_prescriptions_table', 1),
(13, '2025_02_27_000009_create_appointments_table', 1),
(14, '2025_02_27_000010_create_reviews_table', 1),
(15, '2025_02_27_000011_create_orders_table', 1),
(16, '2025_02_27_000012_create_order_items_table', 1),
(17, '2025_02_27_000013_create_ships_table', 1),
(18, '2025_02_27_000014_create_transactions_table', 1),
(19, '2025_02_27_090502_add_foreign_keys_to_tables', 1),
(20, '2025_03_01_082601_create_sessions_table', 1),
(21, '2025_03_09_104144_modify_status_column_in_users_table', 1),
(22, '2025_03_15_142624_add_status_to_prescriptions_table', 1),
(23, '2025_03_15_142652_create_return_orders_table', 1),
(24, '2025_03_15_144303_create_return_items_table', 1),
(25, '2024_03_16_000001_add_email_verification_token_to_users_table', 2),
(26, '2025_03_21_065308_create_cart_items_table', 3),
(27, '2025_03_30_102045_add_image_to_users_table', 4),
(28, '2025_03_30_102613_remove_image_from_users_table', 5),
(30, '2025_03_30_110904_create_services_table', 6),
(31, '2025_03_30_115828_add_service_and_guest_info_to_appointments_table', 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','credit_card','bank_transfer') NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id_order_item` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `id_cosmetic` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('nhattan.9a7@gmail.com', 'XbWsvU1M5tlayjp8q5nU0lhgohbUCYu2DtKfSH4VbMEy6g88FVt3aiEe4QRcJLdf', '2025-03-19 09:51:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id_prescription` bigint(20) UNSIGNED NOT NULL,
  `id_medical_record` bigint(20) UNSIGNED NOT NULL,
  `medicine` varchar(100) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `frequency` varchar(100) NOT NULL,
  `duration` int(11) NOT NULL,
  `prescribed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ranks`
--

CREATE TABLE `ranks` (
  `id_rank` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `min_points` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ranks`
--

INSERT INTO `ranks` (`id_rank`, `name`, `min_points`, `created_at`, `updated_at`) VALUES
(1, 'Bronze', 5000000, NULL, NULL),
(2, 'Silver', 15000000, NULL, NULL),
(3, 'Gold', 30000000, NULL, NULL),
(4, 'Member', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `return_items`
--

CREATE TABLE `return_items` (
  `id_return_item` bigint(20) UNSIGNED NOT NULL,
  `return_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` text NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `return_orders`
--

CREATE TABLE `return_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `return_type` enum('refund','exchange') NOT NULL,
  `id_status` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `total_refund` decimal(10,2) NOT NULL DEFAULT 0.00,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id_review` bigint(20) UNSIGNED NOT NULL,
  `id_cosmetic` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id_review`, `id_cosmetic`, `id_user`, `comment`, `rating`, `created_at`, `updated_at`) VALUES
(1, 11, 2, 'cũng xịn', 3, '2025-03-30 07:36:17', '2025-03-30 08:33:14'),
(2, 1, 7, 'hay', 5, '2025-03-30 11:25:54', '2025-03-30 11:25:54'),
(11, 11, 7, 'siêu tệ', 1, '2025-03-30 11:56:54', '2025-03-30 11:56:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id_role`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'doctor', NULL, NULL),
(3, 'pharmacist', NULL, NULL),
(4, 'customer', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id_service` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên dịch vụ',
  `description` text DEFAULT NULL COMMENT 'Mô tả dịch vụ',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Giá dịch vụ',
  `duration` int(11) NOT NULL DEFAULT 30 COMMENT 'Thời gian dịch vụ (phút)',
  `image` varchar(255) DEFAULT NULL COMMENT 'Hình ảnh dịch vụ',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái kích hoạt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`id_service`, `name`, `description`, `price`, `duration`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Khám tổng quát', 'Dịch vụ khám sức khỏe tổng quát toàn diện, bao gồm kiểm tra các chỉ số cơ bản, huyết áp, tim mạch và tư vấn sức khỏe.', 500000.00, 45, 'user/theme/images/products/service-1.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(2, 'Khám da liễu', 'Dịch vụ khám và điều trị các vấn đề về da, bao gồm mụn trứng cá, viêm da, dị ứng da và các bệnh lý da liễu khác.', 400000.00, 30, 'user/theme/images/products/service-2.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(3, 'Khám nha khoa', 'Dịch vụ khám, làm sạch răng và tư vấn về sức khỏe răng miệng.', 350000.00, 40, 'user/theme/images/products/service-3.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(4, 'Xét nghiệm máu', 'Dịch vụ xét nghiệm máu toàn diện, kiểm tra chỉ số đường huyết, mỡ máu và các chỉ số quan trọng khác.', 250000.00, 15, 'user/theme/images/products/service-4.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(5, 'Siêu âm ổ bụng', 'Dịch vụ siêu âm kiểm tra các cơ quan nội tạng trong ổ bụng như gan, thận, túi mật.', 450000.00, 20, 'user/theme/images/products/service-6.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(6, 'Khám tim mạch', 'Dịch vụ khám chuyên sâu về tim mạch, bao gồm đo điện tâm đồ và tư vấn phòng ngừa bệnh tim mạch.', 600000.00, 35, 'user/theme/images/products/service-8.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(7, 'Khám mắt', 'Dịch vụ khám thị lực, kiểm tra sức khỏe mắt và tư vấn các vấn đề về thị giác.', 300000.00, 25, 'user/theme/images/products/service-1.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(8, 'Châm cứu', 'Dịch vụ châm cứu điều trị đau nhức, cải thiện tuần hoàn máu và phục hồi sức khỏe.', 350000.00, 40, 'user/theme/images/products/service-2.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(9, 'Vật lý trị liệu', 'Dịch vụ vật lý trị liệu giúp phục hồi chức năng vận động, giảm đau và tăng cường sức khỏe cơ xương khớp.', 400000.00, 45, 'user/theme/images/products/service-3.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19'),
(10, 'Tư vấn dinh dưỡng', 'Dịch vụ tư vấn chế độ dinh dưỡng, xây dựng thực đơn phù hợp với tình trạng sức khỏe của từng cá nhân.', 300000.00, 30, 'user/theme/images/products/service-4.jpg', 1, '2025-03-30 04:36:19', '2025-03-30 04:36:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ships`
--

CREATE TABLE `ships` (
  `id_ship` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `distance` decimal(10,2) DEFAULT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  `status` enum('pending','shipping','delivered','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--

CREATE TABLE `transactions` (
  `id_transaction` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `points_used` int(11) NOT NULL DEFAULT 0,
  `payment_method` enum('cash','credit_card','bank_transfer') NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `final_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `treatments`
--

CREATE TABLE `treatments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `equipment_needed` text NOT NULL,
  `contraindications` text NOT NULL,
  `side_effects` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `id_rank` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `total_spent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `last_transaction` datetime DEFAULT NULL,
  `failed_appointments` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','temporary_locked','permanent_locked') NOT NULL DEFAULT 'active',
  `email_verification_token` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id_user`, `id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `age`, `gender`, `address`, `points`, `total_spent`, `last_transaction`, `failed_appointments`, `created_at`, `updated_at`, `status`, `email_verification_token`, `avatar`, `email_verified_at`, `remember_token`) VALUES
(1, 1, 1, 'admin', 'admin@gmail.com', '0123456789', '$2y$10$Axm3HYefZKSBCDSeLi0js.T.OiKGfPXALwtYH6lZ9v8.jXBrgqDzi', NULL, NULL, NULL, 0, 0.00, NULL, 0, NULL, '2025-03-30 01:05:20', 'active', NULL, NULL, NULL, NULL),
(2, 4, 1, 'tan', 'nhattan.9a7@gmail.com', '0774649350', '$2y$10$i13IIG02Hu/v5z8YjCXh9uqdLXyREUzHjC1CAySu.digRgjwAVVT.', 20, 'male', '22/255, đường Tân Hòa II, Phường Hiệp phú, Long Thạnh Mỹ, Q.9 - Tp. Thủ Đức - Tp. HCM', 0, 0.00, NULL, 0, '2025-03-15 20:28:51', '2025-03-30 03:24:37', 'active', 'd4ldZhHtYx5vU82YLshXvA41Nn05BFMbdCbFeAcRLAtlkWMuA8Yo1qmtvLOj0VUB', 'avatars/1743330277_shin.png', '2025-03-15 20:28:51', '532FQzjKtAZwENDYFtSG4dZWzzZviAJYp5iVoCnfBsoe7sZFHB8US0gD8NhK'),
(3, 2, 3, 'Lê Tuấn Hải', 'hai@gmail.com', '', '$2y$10$zqGC5i06nE7btIzLMkeg7..wk7kxGW1x9.Lq6EyoWYRdm0EiuU0US', 27, 'male', NULL, 0, 0.00, NULL, 0, '2025-03-30 02:42:41', '2025-03-30 02:42:41', 'active', 'H4tsEBznATCm9M6hhPw1wKkXTfifZWzYFm6QiMsKeBf2iaTLC0D9Cw8crgLHNWFO', NULL, '2025-03-30 02:42:41', NULL),
(4, 2, 2, 'Lê Mỹ', 'my@gmail.com', '', '$2y$10$05sPdD0nSAgvlw3ZI2Fsxu0oshKswX1lBZ5QUeVyWyzUrb8CR35Ie', 33, 'female', NULL, 0, 0.00, NULL, 0, '2025-03-30 02:44:02', '2025-03-30 02:44:02', 'active', 'FdLcBHeuPGOLrxjq3i2tOkR0N79VV9SJxGvlmiX8j3F4pzzJrmi4lfnulTG8OL7s', NULL, '2025-03-30 02:44:02', NULL),
(5, 2, 1, 'Lý Trọng Cát', 'cat@gmail.com', '', '$2y$10$mUGKeMVs8AFIYnsPUMNyMeWKu1fTpXizq0pGGJh7KSYWCMe2nMca.', 44, 'female', NULL, 0, 0.00, NULL, 0, '2025-03-30 02:44:53', '2025-03-30 02:44:53', 'active', 'lN0YyDLzNe9PrltxNADN7EYyszmCqxDxQ2wctbNZ0dbNabdIcdbrPBCpZDK9aoWp', NULL, '2025-03-30 02:44:53', NULL),
(6, 2, 2, 'Nguyễn Trọng Nhân', 'nhan@gmail.com', '', '$2y$10$suUbpNJyHDc9uwH5GRSh3.OGXcwh.bHGxevLLajCkvRV6fBh1QfHK', 46, 'male', NULL, 0, 0.00, NULL, 0, '2025-03-30 02:46:47', '2025-03-30 02:46:47', 'active', '30OUDaQF1E6fBI6dTrA7sY7G0VBmEWpGxyJfTY3CAwpp8p8xP8pwFIVCzgra3z6X', NULL, '2025-03-30 02:46:47', NULL),
(7, 4, 1, 'huy', 'huy@gmail.com', '0123456789', '$2y$10$Go/V20zTpFVSd9lU1xjsl.5Nvz4QrL6ZAKlIVizdtu.d.0H8Re5C2', NULL, NULL, NULL, 0, 0.00, NULL, 0, '2025-03-30 07:36:53', '2025-03-30 10:41:30', 'active', 'd45J1MX8e0Eot6upUnhhInoDxOYUDAlrciplD4fPpY8ZAZiafLXynYfzA60YHyTs', 'avatars/1743356490_1 (1).png', '2025-03-30 07:36:53', '881ycsU1ptMH0wIWwfY3TPN1aSapPotnuzswitrb9ju19gvL3CcwCxdUjAsg');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id_appointment`),
  ADD KEY `fk_appointments_patient` (`id_patient`),
  ADD KEY `fk_appointments_doctor1` (`id_doctor`),
  ADD KEY `appointments_id_service_foreign` (`id_service`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Chỉ mục cho bảng `cosmetics`
--
ALTER TABLE `cosmetics`
  ADD PRIMARY KEY (`id_cosmetic`),
  ADD UNIQUE KEY `cosmetics_id_cosmetic_unique` (`id_cosmetic`),
  ADD KEY `cosmetics_id_category_foreign` (`id_category`);

--
-- Chỉ mục cho bảng `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id_inventory`),
  ADD KEY `inventory_id_cosmetic_foreign` (`id_cosmetic`);

--
-- Chỉ mục cho bảng `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id_medical_record`),
  ADD KEY `fk_medical_records_patient` (`id_patient`),
  ADD KEY `fk_medical_records_doctor` (`id_doctor`);

--
-- Chỉ mục cho bảng `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `fk_orfers_id_user` (`id_user`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id_order_item`),
  ADD KEY `fk_order_items_id_order` (`id_order`),
  ADD KEY `fk_order_items_id_cosmetic` (`id_cosmetic`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id_prescription`),
  ADD KEY `fk__prescriptions_id_medical_records` (`id_medical_record`);

--
-- Chỉ mục cho bảng `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id_rank`);

--
-- Chỉ mục cho bảng `return_items`
--
ALTER TABLE `return_items`
  ADD PRIMARY KEY (`id_return_item`),
  ADD KEY `return_items_return_id_foreign` (`return_id`),
  ADD KEY `return_items_order_item_id_foreign` (`order_item_id`);

--
-- Chỉ mục cho bảng `return_orders`
--
ALTER TABLE `return_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_orders_order_id_foreign` (`order_id`),
  ADD KEY `return_orders_processed_by_foreign` (`processed_by`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `fk_reviews_id_cosmetic` (`id_cosmetic`),
  ADD KEY `fk_reviews_id_user` (`id_user`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_service`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `ships`
--
ALTER TABLE `ships`
  ADD PRIMARY KEY (`id_ship`),
  ADD UNIQUE KEY `ships_id_order_unique` (`id_order`);

--
-- Chỉ mục cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id_transaction`),
  ADD KEY `fk_transactions_id_user` (`id_user`),
  ADD KEY `fk_transactions_id_order` (`id_order`);

--
-- Chỉ mục cho bảng `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_id_role_foreign` (`id_role`),
  ADD KEY `users_id_rank_foreign` (`id_rank`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id_appointment` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `cosmetics`
--
ALTER TABLE `cosmetics`
  MODIFY `id_cosmetic` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id_inventory` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id_medical_record` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id_prescription` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ranks`
--
ALTER TABLE `ranks`
  MODIFY `id_rank` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `return_items`
--
ALTER TABLE `return_items`
  MODIFY `id_return_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `return_orders`
--
ALTER TABLE `return_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id_service` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `ships`
--
ALTER TABLE `ships`
  MODIFY `id_ship` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id_transaction` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_id_doctor_foreign` FOREIGN KEY (`id_doctor`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `appointments_id_patient_foreign` FOREIGN KEY (`id_patient`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `appointments_id_service_foreign` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`),
  ADD CONSTRAINT `fk_appointments_doctor1` FOREIGN KEY (`id_doctor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointments_patient` FOREIGN KEY (`id_patient`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cosmetics`
--
ALTER TABLE `cosmetics`
  ADD CONSTRAINT `cosmetics_id_category_foreign` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`);

--
-- Các ràng buộc cho bảng `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_id_cosmetic_foreign` FOREIGN KEY (`id_cosmetic`) REFERENCES `cosmetics` (`id_cosmetic`);

--
-- Các ràng buộc cho bảng `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `fk_medical_records_doctor` FOREIGN KEY (`id_doctor`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `fk_medical_records_patient` FOREIGN KEY (`id_patient`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `medical_records_id_doctor_foreign` FOREIGN KEY (`id_doctor`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `medical_records_id_patient_foreign` FOREIGN KEY (`id_patient`) REFERENCES `users` (`id_user`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orfers_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `orders_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_id_cosmetic` FOREIGN KEY (`id_cosmetic`) REFERENCES `cosmetics` (`id_cosmetic`),
  ADD CONSTRAINT `fk_order_items_id_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `order_items_id_cosmetic_foreign` FOREIGN KEY (`id_cosmetic`) REFERENCES `cosmetics` (`id_cosmetic`),
  ADD CONSTRAINT `order_items_id_order_foreign` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`);

--
-- Các ràng buộc cho bảng `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `fk__prescriptions_id_medical_records` FOREIGN KEY (`id_medical_record`) REFERENCES `medical_records` (`id_medical_record`),
  ADD CONSTRAINT `prescriptions_id_medical_record_foreign` FOREIGN KEY (`id_medical_record`) REFERENCES `medical_records` (`id_medical_record`);

--
-- Các ràng buộc cho bảng `return_items`
--
ALTER TABLE `return_items`
  ADD CONSTRAINT `return_items_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id_order_item`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_items_return_id_foreign` FOREIGN KEY (`return_id`) REFERENCES `return_orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `return_orders`
--
ALTER TABLE `return_orders`
  ADD CONSTRAINT `return_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `return_orders_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_id_cosmetic` FOREIGN KEY (`id_cosmetic`) REFERENCES `cosmetics` (`id_cosmetic`),
  ADD CONSTRAINT `fk_reviews_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `reviews_id_cosmetic_foreign` FOREIGN KEY (`id_cosmetic`) REFERENCES `cosmetics` (`id_cosmetic`),
  ADD CONSTRAINT `reviews_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Các ràng buộc cho bảng `ships`
--
ALTER TABLE `ships`
  ADD CONSTRAINT `fk_ships_id_orders` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `ships_id_order_foreign` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`);

--
-- Các ràng buộc cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_id_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `fk_transactions_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `transactions_id_order_foreign` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `transactions_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_rank_foreign` FOREIGN KEY (`id_rank`) REFERENCES `ranks` (`id_rank`),
  ADD CONSTRAINT `users_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
