-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 14, 2025 lúc 06:29 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

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
(1, 17, 2, 6, NULL, NULL, NULL, '2025-04-18 20:47:00', 'scheduled', 'mắc ẻ', '2025-04-11 06:48:30', '2025-04-11 06:48:30'),
(2, 17, 2, 1, NULL, NULL, NULL, '2025-04-11 20:50:00', 'completed', 'mắc ẻ', '2025-04-11 06:50:24', '2025-04-11 06:52:28'),
(3, 17, 2, 7, NULL, NULL, NULL, '2025-04-13 23:19:00', 'completed', NULL, '2025-04-13 05:22:54', '2025-04-13 05:23:27'),
(4, 19, 2, 1, NULL, NULL, NULL, '2025-04-13 08:17:00', 'scheduled', 'Nổi mụn', '2025-04-13 10:18:10', '2025-04-13 10:18:10');

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
(1, 'Mỹ phẩm dưỡng da', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(2, 'Mỹ phẩm trang điểm', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(3, 'Mỹ phẩm chống nắng', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(4, 'Mỹ phẩm trị mụn', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(5, 'Mỹ phẩm dưỡng tóc', '2025-04-09 01:31:09', '2025-04-09 01:31:09');

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
(6, 3, 'Xịt khoáng Vichy', 280000.00, 4.20, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(7, 2, 'Gel trị mụn Some By Mi', 350000.00, 4.60, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(8, 4, 'Dầu gội Pantene', 150000.00, 4.00, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(9, 4, 'Kem ủ tóc Tresemme', 195000.00, 4.20, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(10, 2, 'Serum trị mụn Tea Tree The Body Shop', 295000.00, 4.40, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(11, 1, 'Phấn má hồng NARS Orgasm', 750000.00, 4.80, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(12, 5, 'Kem chống nắng Bioré', 185000.00, 4.50, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(13, 3, 'Mặt nạ Innisfree Green Tea', 30000.00, 4.30, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(14, 3, 'Nước hoa hồng Thayers', 280000.00, 4.60, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(15, 1, 'Mascara Maybelline Lash Sensational', 160000.00, 4.40, 0, 'user/theme/images/products/decu2.jpg', '2025-04-09 01:31:09', '2025-04-09 01:31:09'),
(17, 1, 'Sữa tắm gội em bé Gentle Wash 500ml La Beauty', 159000.00, 5.00, 0, 'user/theme/images/products/la1.jpg', '2025-03-16 10:56:04', '2025-03-30 04:25:54'),
(18, 2, 'Gel chấm mụn, mờ thâm Decumar Advance THC 20g', 105000.00, 4.00, 0, 'user/theme/images/products/fix1.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(19, 3, 'Dung dịch vệ sinh vùng kín Bimunica 250ml', 184000.00, 4.80, 0, 'user/theme/images/products/gam1.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(20, 1, 'Dầu Dừa Tươi Raw Virgin Coconut Oil Coboté 50ml', 81000.00, 4.20, 0, 'user/theme/images/products/la2.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(21, 2, 'Kem giảm thâm vùng nách, mông, bikini Neotherica Armpil Cream', 139000.00, 3.90, 0, 'user/theme/images/products/fix2.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(22, 3, 'Gel rửa mặt SVR Sebiaclear Gel Moussant 200ml', 390000.00, 4.70, 0, 'user/theme/images/products/gam2.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(23, 4, 'Bọt vệ sinh nam giới Sumely Mens Sanitary Foam', 96000.00, 4.00, 0, 'user/theme/images/products/decu1.jpg', '2025-03-16 10:56:04', '2025-04-13 08:50:58'),
(24, 4, 'Sữa rửa mặt On: The Body Rice Therapy Heartleaf Acne Cleanser', 165000.00, 4.30, 0, 'user/theme/images/products/decu2.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(25, 5, 'Kem chống nắng La Roche-Posay Anthelios UVMune 400 SPF50+', 450000.00, 4.90, 0, 'user/theme/images/products/laro1.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(26, 5, 'Nước tẩy trang La Roche-Posay Micellar Water Ultra', 395000.00, 4.60, 0, 'user/theme/images/products/laro2.jpg', '2025-03-16 10:56:04', '2025-03-16 10:56:04'),
(27, 1, 'Mặt nạ dưỡng ẩm La Beauty Hydrating Mask', 75000.00, 2.00, 0, 'user/theme/images/products/la3.jpg', '2025-03-16 10:56:04', '2025-03-30 05:09:10'),
(28, 2, 'Serum trị mụn Fixderma Acne Clear Solution', 220000.00, 4.00, 0, 'user/theme/images/products/fix3.jpg', '2025-03-16 10:56:04', '2025-04-13 10:14:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `repeat_weekly` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `doctor_schedules`
--

INSERT INTO `doctor_schedules` (`id`, `doctor_id`, `date`, `start_time`, `end_time`, `is_available`, `repeat_weekly`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-04-13', '08:00:00', '17:00:00', 1, 0, NULL, '2025-04-12 02:30:44', '2025-04-12 02:30:44'),
(2, 3, '2025-04-12', '09:00:00', '18:00:00', 1, 0, NULL, '2025-04-12 02:31:46', '2025-04-12 02:31:46'),
(4, 2, '2025-04-12', '08:00:00', '18:00:00', 1, 0, NULL, '2025-04-12 02:40:01', '2025-04-12 02:40:01'),
(5, 2, '2025-04-15', '08:00:00', '17:00:00', 1, 0, NULL, '2025-04-13 05:55:38', '2025-04-13 05:55:38'),
(6, 2, '2025-04-14', '08:00:00', '17:00:00', 1, 0, NULL, '2025-04-13 05:55:56', '2025-04-13 05:55:56');

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
-- Cấu trúc bảng cho bảng `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('in','out') NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `medicine_id`, `quantity`, `type`, `note`, `user_id`, `batch_number`, `expiry_date`, `supplier`, `unit_price`, `created_at`, `updated_at`) VALUES
(29, 8, -10, 'out', 'Xuất cho đơn thuốc #2', 5, NULL, NULL, NULL, NULL, '2025-04-10 10:25:31', '2025-04-10 10:25:31'),
(34, 6, -1, 'out', 'Xuất cho đơn thuốc #1', 5, NULL, NULL, NULL, NULL, '2025-04-10 10:33:31', '2025-04-10 10:33:31'),
(35, 9, -3, 'out', 'Xuất cho đơn thuốc #1', 5, NULL, NULL, NULL, NULL, '2025-04-10 10:33:31', '2025-04-10 10:33:31'),
(36, 8, -1, 'out', 'Xuất cho đơn thuốc #3', 5, NULL, NULL, NULL, NULL, '2025-04-10 10:56:47', '2025-04-10 10:56:47'),
(37, 7, -3, 'out', 'Xuất cho đơn thuốc #3', 5, NULL, NULL, NULL, NULL, '2025-04-10 10:56:47', '2025-04-10 10:56:47'),
(38, 8, -1, 'out', 'Xuất cho đơn thuốc #3', 5, NULL, NULL, NULL, NULL, '2025-04-10 11:11:29', '2025-04-10 11:11:29'),
(39, 7, -3, 'out', 'Xuất cho đơn thuốc #3', 5, NULL, NULL, NULL, NULL, '2025-04-10 11:11:29', '2025-04-10 11:11:29'),
(40, 9, -1, 'out', 'Xuất cho đơn thuốc #4', 5, NULL, NULL, NULL, NULL, '2025-04-11 06:34:38', '2025-04-11 06:34:38'),
(41, 3, -2, 'out', 'Xuất cho đơn thuốc #4', 5, NULL, NULL, NULL, NULL, '2025-04-11 06:34:38', '2025-04-11 06:34:38'),
(42, 8, -1, 'out', 'Xuất cho đơn thuốc #6', 5, NULL, NULL, NULL, NULL, '2025-04-13 10:35:12', '2025-04-13 10:35:12'),
(43, 3, -1, 'out', 'Xuất cho đơn thuốc #6', 5, NULL, NULL, NULL, NULL, '2025-04-13 10:35:12', '2025-04-13 10:35:12'),
(44, 8, -1, 'out', 'Xuất cho đơn thuốc #5', 5, NULL, NULL, NULL, NULL, '2025-04-14 09:28:54', '2025-04-14 09:28:54');

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

--
-- Đang đổ dữ liệu cho bảng `medical_records`
--

INSERT INTO `medical_records` (`id_medical_record`, `id_patient`, `id_doctor`, `diagnosis`, `notes`, `pdf_url`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 'mặt dày', NULL, NULL, '2025-04-06 09:57:14', '2025-04-06 09:59:27'),
(2, 9, 4, 'ngu', NULL, NULL, '2025-04-09 01:07:00', '2025-04-09 01:08:32'),
(3, 13, 2, 'mọc mụn cóc', NULL, NULL, '2025-04-10 10:47:11', '2025-04-10 10:48:33'),
(4, 11, 2, 'ngu', NULL, NULL, '2025-04-11 06:31:02', '2025-04-11 06:32:35'),
(5, 17, 2, NULL, 'mắc ẻ', NULL, '2025-04-11 06:52:28', '2025-04-11 06:52:28'),
(6, 17, 2, 'ốm 1', NULL, NULL, '2025-04-12 02:35:34', '2025-04-13 05:54:22'),
(7, 17, 2, 'ốm', NULL, NULL, '2025-04-13 05:23:27', '2025-04-13 05:53:59');

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

--
-- Đang đổ dữ liệu cho bảng `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `description`, `price`, `stock_quantity`, `manufacturer`, `expiry_date`, `dosage_form`, `usage_instructions`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Retinol Serum', 'Serum chứa Retinol giúp làm mờ nếp nhăn, cải thiện kết cấu da', 550000.00, 50, 'The Ordinary', '2026-04-06', 'Serum', 'Sử dụng vào buổi tối, tránh tiếp xúc với ánh nắng trực tiếp', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(2, 'Vitamin C Serum', 'Serum Vitamin C giúp làm sáng da, chống oxy hóa', 650000.00, 40, 'SkinCeuticals', '2026-04-06', 'Serum', 'Sử dụng vào buổi sáng, kết hợp với kem chống nắng', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(3, 'Hyaluronic Acid', 'Cấp ẩm sâu cho da, giúp da căng mọng', 450000.00, 57, 'La Roche-Posay', '2026-04-06', 'Serum', 'Sử dụng sáng và tối trên da ẩm', '2025-04-06 09:41:45', '2025-04-13 10:35:12', NULL),
(4, 'Niacinamide', 'Giảm mụn, thu nhỏ lỗ chân lông, cân bằng dầu', 350000.00, 45, 'The Ordinary', '2026-04-06', 'Serum', 'Sử dụng sáng và tối sau khi rửa mặt', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(5, 'Salicylic Acid', 'Loại bỏ tế bào chết, thông thoáng lỗ chân lông, giảm mụn', 380000.00, 35, 'Paula\'s Choice', '2026-04-06', 'Dung dịch', 'Sử dụng 1-2 lần/tuần vào buổi tối', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(6, 'Kem dưỡng ẩm Ceramide', 'Phục hồi hàng rào bảo vệ da, cấp ẩm sâu', 480000.00, 29, 'CeraVe', '2026-04-06', 'Kem', 'Sử dụng sáng và tối sau serum', '2025-04-06 09:41:45', '2025-04-10 10:33:31', NULL),
(7, 'Kem chống nắng SPF 50', 'Bảo vệ da khỏi tia UVA/UVB, ngăn ngừa lão hóa sớm', 420000.00, 49, 'La Roche-Posay', '2026-04-06', 'Kem', 'Sử dụng mỗi sáng, thoa lại sau 2 giờ nếu tiếp xúc với ánh nắng', '2025-04-06 09:41:45', '2025-04-10 11:11:29', NULL),
(8, 'Gel trị mụn Benzoyl Peroxide', 'Tiêu diệt vi khuẩn gây mụn, giảm viêm', 320000.00, 26, 'Effaclar', '2026-04-06', 'Gel', 'Thoa lên vùng mụn vào buổi tối', '2025-04-06 09:41:45', '2025-04-14 09:28:54', NULL),
(9, 'Mặt nạ làm dịu Aloe Vera', 'Làm dịu và cấp ẩm cho da kích ứng', 180000.00, 66, 'Innisfree', '2026-04-06', 'Mặt nạ', 'Đắp 15-20 phút, 1-2 lần/tuần', '2025-04-06 09:41:45', '2025-04-11 06:34:38', NULL),
(10, 'Tẩy tế bào chết AHA/BHA', 'Loại bỏ tế bào chết, làm sáng da, giảm mụn ẩn', 520000.00, 25, 'Cosrx', '2026-04-06', 'Dung dịch', 'Sử dụng 1-2 lần/tuần vào buổi tối', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL);

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
(6, '2024_03_16_000001_add_email_verification_token_to_users_table', 1),
(7, '2025_02_27_000001_create_roles_table', 1),
(8, '2025_02_27_000002_create_ranks_table', 1),
(9, '2025_02_27_000004_create_categories_table', 1),
(10, '2025_02_27_000005_create_cosmetics_table', 1),
(11, '2025_02_27_000006_create_inventory_table', 1),
(12, '2025_02_27_000007_create_medical_records_table', 1),
(13, '2025_02_27_000008_create_prescriptions_table', 1),
(14, '2025_02_27_000009_create_appointments_table', 1),
(15, '2025_02_27_000010_create_reviews_table', 1),
(16, '2025_02_27_000011_create_orders_table', 1),
(17, '2025_02_27_000012_create_order_items_table', 1),
(18, '2025_02_27_000013_create_ships_table', 1),
(19, '2025_02_27_000014_create_transactions_table', 1),
(20, '2025_02_27_090502_add_foreign_keys_to_tables', 1),
(21, '2025_03_01_082601_create_sessions_table', 1),
(22, '2025_03_09_104144_modify_status_column_in_users_table', 1),
(23, '2025_03_15_142624_add_status_to_prescriptions_table', 1),
(24, '2025_03_15_142652_create_return_orders_table', 1),
(25, '2025_03_15_144303_create_return_items_table', 1),
(26, '2025_03_30_102045_add_image_to_users_table', 1),
(27, '2025_03_30_102613_remove_image_from_users_table', 1),
(28, '2025_03_30_110904_create_services_table', 1),
(29, '2025_03_30_115828_add_service_and_guest_info_to_appointments_table', 1),
(30, '2025_04_06_160338_add_patient_and_doctor_id_to_prescriptions_table', 1),
(31, '2025_04_06_172900_add_diagnosis_notes_columns_to_prescriptions', 1),
(32, '2025_04_06_173750_create_prescription_items_table', 2),
(33, '2025_04_06_174300_update_prescriptions_table_structure', 3),
(34, '2025_04_06_175800_add_specialization_to_users_table', 4),
(35, '2025_04_07_141845_create_inventory_logs_table', 5),
(36, '2025_04_10_171403_fix_status_column_in_orders_table', 6),
(39, '2025_04_10_170307_fix_status_column_in_orders_table', 7),
(40, '2025_04_10_175524_add_payment_fields_to_prescriptions_table', 7),
(41, '2025_04_12_083159_create_schedules_table', 7),
(42, '2025_04_14_create_order_status_timestamps', 8),
(43, '2025_04_06_095229_add_oauth_columns_to_users_table', 9),
(44, '2025_04_13_094630_add_social_auth_columns_to_users_table', 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','credit_card','bank_transfer') NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transaction_id` varchar(255) DEFAULT NULL,
  `shipping_name` varchar(255) DEFAULT NULL,
  `shipping_phone` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `shipping_ward` varchar(255) DEFAULT NULL,
  `shipping_district` varchar(255) DEFAULT NULL,
  `shipping_province` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `total_price`, `payment_method`, `status`, `created_at`, `updated_at`, `confirmed_at`, `shipped_at`, `delivered_at`, `cancellation_reason`, `payment_status`, `subtotal`, `shipping_fee`, `tax`, `discount`, `transaction_id`, `shipping_name`, `shipping_phone`, `shipping_address`, `shipping_ward`, `shipping_district`, `shipping_province`, `notes`) VALUES
(5, 9, 3200000.00, 'cash', 'done', '2025-04-10 10:25:31', '2025-04-10 10:25:31', NULL, NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 7, 1020000.00, 'cash', 'done', '2025-04-10 10:33:31', '2025-04-10 10:33:31', NULL, NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 17, 462000.00, 'cash', 'confirmed', '2025-04-13 01:54:47', '2025-04-13 03:32:08', '2025-04-13 03:32:08', NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 17, 312000.00, 'credit_card', 'confirmed', '2025-04-13 01:57:29', '2025-04-13 03:13:14', '2025-04-13 03:13:14', NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 17, 312000.00, 'credit_card', 'pending', '2025-04-13 03:41:26', '2025-04-13 03:41:26', NULL, NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 17, 62000.00, 'credit_card', 'pending', '2025-04-13 03:49:13', '2025-04-13 03:49:13', NULL, NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 17, 352000.00, 'credit_card', 'cancelled', '2025-04-13 04:53:29', '2025-04-13 04:53:54', NULL, NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 17, 192000.00, 'credit_card', 'pending', '2025-04-13 04:55:35', '2025-04-13 04:55:35', NULL, NULL, NULL, NULL, 'pending', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 17, 312000.00, 'credit_card', 'delivered', '2025-04-13 05:17:11', '2025-04-13 05:18:57', '2025-04-13 05:18:31', '2025-04-13 05:18:52', '2025-04-13 05:18:57', NULL, 'paid', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 17, 472000.00, 'credit_card', 'delivered', '2025-04-13 08:36:17', '2025-04-13 08:39:52', '2025-04-13 08:39:19', '2025-04-13 08:39:41', '2025-04-13 08:39:51', NULL, 'paid', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 19, 252000.00, 'credit_card', 'pending', '2025-04-13 10:16:37', '2025-04-13 10:16:37', NULL, NULL, NULL, NULL, 'paid', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 17, 252000.00, 'credit_card', 'pending', '2025-04-13 10:40:25', '2025-04-13 10:40:25', NULL, NULL, NULL, NULL, 'paid', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id_order_item`, `id_order`, `id_cosmetic`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 5, 8, 10, 320000.00, '2025-04-10 10:25:31', '2025-04-10 10:25:31'),
(2, 6, 6, 1, 480000.00, '2025-04-10 10:33:31', '2025-04-10 10:33:31'),
(3, 6, 9, 3, 180000.00, '2025-04-10 10:33:31', '2025-04-10 10:33:31'),
(44, 27, 14, 1, 280000.00, '2025-04-13 01:54:47', '2025-04-13 01:54:47'),
(45, 27, 8, 1, 150000.00, '2025-04-13 01:54:47', '2025-04-13 01:54:47'),
(46, 28, 14, 1, 280000.00, '2025-04-13 01:57:29', '2025-04-13 01:57:29'),
(47, 29, 14, 1, 280000.00, '2025-04-13 03:41:26', '2025-04-13 03:41:26'),
(48, 30, 13, 1, 30000.00, '2025-04-13 03:49:13', '2025-04-13 03:49:13'),
(49, 31, 15, 2, 160000.00, '2025-04-13 04:53:29', '2025-04-13 04:53:29'),
(50, 32, 15, 1, 160000.00, '2025-04-13 04:55:35', '2025-04-13 04:55:35'),
(51, 33, 14, 1, 280000.00, '2025-04-13 05:17:11', '2025-04-13 05:17:11'),
(52, 34, 28, 2, 220000.00, '2025-04-13 08:36:17', '2025-04-13 08:36:17'),
(53, 35, 28, 1, 220000.00, '2025-04-13 10:16:37', '2025-04-13 10:16:37'),
(54, 36, 28, 1, 220000.00, '2025-04-13 10:40:25', '2025-04-13 10:40:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `id_patient` bigint(20) UNSIGNED DEFAULT NULL,
  `id_doctor` bigint(20) UNSIGNED DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `prescriptions`
--

INSERT INTO `prescriptions` (`id_prescription`, `id_medical_record`, `id_patient`, `id_doctor`, `diagnosis`, `notes`, `created_at`, `updated_at`, `status`, `payment_method`, `payment_id`, `payment_status`, `total_amount`, `processed_by`, `processed_at`) VALUES
(1, 1, 7, 2, 'mặt dày', NULL, '2025-04-06 09:59:27', '2025-04-10 10:33:31', 'completed', NULL, NULL, NULL, 1020000.00, 5, '2025-04-10 10:33:31'),
(2, 2, 9, 4, 'ngu', NULL, '2025-04-09 01:08:32', '2025-04-10 10:25:31', 'completed', NULL, NULL, NULL, 3200000.00, 5, '2025-04-10 10:25:31'),
(3, 3, 13, 2, 'mọc mụn cóc', NULL, '2025-04-10 10:48:33', '2025-04-10 10:48:33', 'completed', NULL, NULL, NULL, NULL, 5, '2025-04-10 11:11:29'),
(4, 4, 11, 2, 'ngu', NULL, '2025-04-11 06:32:35', '2025-04-11 06:32:35', 'completed', NULL, NULL, NULL, NULL, 5, '2025-04-11 06:34:38'),
(5, 7, 17, 2, 'ốm', NULL, '2025-04-13 05:53:59', '2025-04-13 05:53:59', 'completed', 'card', 'pi_3RDpsNINxgJ0qCAB1NxV9DA4', 'paid', NULL, 5, '2025-04-14 09:28:54'),
(6, 6, 17, 2, 'ốm 1', NULL, '2025-04-13 05:54:22', '2025-04-13 05:54:22', 'completed', 'cash', NULL, 'completed', NULL, 5, '2025-04-13 10:35:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `prescription_items`
--

CREATE TABLE `prescription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prescription_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `dosage` varchar(255) NOT NULL,
  `instructions` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `prescription_items`
--

INSERT INTO `prescription_items` (`id`, `prescription_id`, `medicine_id`, `quantity`, `dosage`, `instructions`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1, '1 viên/lần', 'Trước khi đi ngủ', 480000.00, '2025-04-06 09:59:27', '2025-04-06 09:59:27'),
(2, 1, 9, 3, '1 cái / lần', 'Trước khi đi ngủ', 180000.00, '2025-04-06 09:59:27', '2025-04-06 09:59:27'),
(3, 2, 8, 10, '1vien/lan', 'sau an', 320000.00, '2025-04-09 01:08:32', '2025-04-09 01:08:32'),
(4, 3, 8, 1, '1vien/lan', 'sau an', 320000.00, '2025-04-10 10:48:33', '2025-04-10 10:48:33'),
(5, 3, 7, 3, '1 ml', 'sau an', 420000.00, '2025-04-10 10:48:33', '2025-04-10 10:48:33'),
(6, 4, 9, 1, '1 cái / lần', 'Trước khi đi ngủ', 180000.00, '2025-04-11 06:32:35', '2025-04-11 06:32:35'),
(7, 4, 3, 2, '1 ml /lần', 'Trước khi ngủ', 450000.00, '2025-04-11 06:32:35', '2025-04-11 06:32:35'),
(8, 5, 8, 1, '1vien/lan', 'sau an', 320000.00, '2025-04-13 05:53:59', '2025-04-13 05:53:59'),
(9, 6, 8, 1, '1vien/lan', 'sau an', 320000.00, '2025-04-13 05:54:22', '2025-04-13 05:54:22'),
(10, 6, 3, 1, '123', '123', 450000.00, '2025-04-13 05:54:22', '2025-04-13 05:54:22');

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
(1, 'bronze', 0, '2025-04-06 09:39:39', '2025-04-06 09:39:39'),
(2, 'silver', 100, '2025-04-06 09:39:39', '2025-04-06 09:39:39'),
(3, 'gold', 500, '2025-04-06 09:39:39', '2025-04-06 09:39:39'),
(4, 'platinum', 1000, '2025-04-06 09:39:39', '2025-04-06 09:39:39');

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
(1, 23, 17, 'chưa đủ wow', 4, '2025-04-13 08:50:58', '2025-04-13 08:50:58'),
(2, 28, 19, 'Sản phẩm tốt', 4, '2025-04-13 10:14:26', '2025-04-13 10:14:26');

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
(1, 'admin', '2025-04-06 09:39:39', '2025-04-06 09:39:39'),
(2, 'doctor', '2025-04-06 09:39:39', '2025-04-06 09:39:39'),
(3, 'pharmacist', '2025-04-06 09:39:39', '2025-04-06 09:39:39'),
(4, 'customer', '2025-04-06 09:39:39', '2025-04-06 09:39:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Khám tổng quát', 'Dịch vụ khám sức khỏe tổng quát toàn diện, bao gồm kiểm tra các chỉ số cơ bản, huyết áp, tim mạch và tư vấn sức khỏe.', 500000.00, 45, 'user/theme/images/products/service-1.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(2, 'Khám da liễu', 'Dịch vụ khám và điều trị các vấn đề về da, bao gồm mụn trứng cá, viêm da, dị ứng da và các bệnh lý da liễu khác.', 400000.00, 30, 'user/theme/images/products/service-2.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(3, 'Khám nha khoa', 'Dịch vụ khám, làm sạch răng và tư vấn về sức khỏe răng miệng.', 350000.00, 40, 'user/theme/images/products/service-3.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(4, 'Xét nghiệm máu', 'Dịch vụ xét nghiệm máu toàn diện, kiểm tra chỉ số đường huyết, mỡ máu và các chỉ số quan trọng khác.', 250000.00, 15, 'user/theme/images/products/service-4.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(5, 'Siêu âm ổ bụng', 'Dịch vụ siêu âm kiểm tra các cơ quan nội tạng trong ổ bụng như gan, thận, túi mật.', 450000.00, 20, 'user/theme/images/products/service-6.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(6, 'Khám tim mạch', 'Dịch vụ khám chuyên sâu về tim mạch, bao gồm đo điện tâm đồ và tư vấn phòng ngừa bệnh tim mạch.', 600000.00, 35, 'user/theme/images/products/service-8.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(7, 'Khám mắt', 'Dịch vụ khám thị lực, kiểm tra sức khỏe mắt và tư vấn các vấn đề về thị giác.', 300000.00, 25, 'user/theme/images/products/service-1.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(8, 'Châm cứu', 'Dịch vụ châm cứu điều trị đau nhức, cải thiện tuần hoàn máu và phục hồi sức khỏe.', 350000.00, 40, 'user/theme/images/products/service-2.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(9, 'Vật lý trị liệu', 'Dịch vụ vật lý trị liệu giúp phục hồi chức năng vận động, giảm đau và tăng cường sức khỏe cơ xương khớp.', 400000.00, 45, 'user/theme/images/products/service-3.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37'),
(10, 'Tư vấn dinh dưỡng', 'Dịch vụ tư vấn chế độ dinh dưỡng, xây dựng thực đơn phù hợp với tình trạng sức khỏe của từng cá nhân.', 300000.00, 30, 'user/theme/images/products/service-4.jpg', 1, '2025-04-06 09:28:37', '2025-04-06 09:28:37');

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

--
-- Đang đổ dữ liệu cho bảng `ships`
--

INSERT INTO `ships` (`id_ship`, `id_order`, `address`, `distance`, `shipping_fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 27, 'Tân Bình', 0.00, 30000.00, 'pending', '2025-04-13 01:54:47', '2025-04-13 01:54:47'),
(2, 28, 'Tân Bình', 0.00, 30000.00, 'pending', '2025-04-13 01:57:29', '2025-04-13 01:57:29'),
(3, 29, 'Tân Bình', 0.00, 30000.00, 'pending', '2025-04-13 03:41:26', '2025-04-13 03:41:26'),
(4, 30, 'Tân Bình', 0.00, 30000.00, 'pending', '2025-04-13 03:49:13', '2025-04-13 03:49:13'),
(5, 31, 'Tân Bình', 0.00, 30000.00, 'failed', '2025-04-13 04:53:29', '2025-04-13 04:53:54'),
(6, 32, 'hcm', 0.00, 30000.00, 'pending', '2025-04-13 04:55:35', '2025-04-13 04:55:35'),
(7, 33, 'hcm1', 0.00, 30000.00, 'pending', '2025-04-13 05:17:11', '2025-04-13 05:17:11'),
(8, 34, 'Tân Bình', 0.00, 30000.00, 'pending', '2025-04-13 08:36:17', '2025-04-13 08:36:17'),
(9, 35, '123 Trường Chinh, Tân Bình, Hồ Chí Minh', 0.00, 30000.00, 'pending', '2025-04-13 10:16:37', '2025-04-13 10:16:37'),
(10, 36, 'Tân Bình', 0.00, 30000.00, 'pending', '2025-04-13 10:40:25', '2025-04-13 10:40:25');

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

--
-- Đang đổ dữ liệu cho bảng `transactions`
--

INSERT INTO `transactions` (`id_transaction`, `id_user`, `id_order`, `amount`, `points_earned`, `points_used`, `payment_method`, `transaction_date`, `final_amount`, `created_at`, `updated_at`) VALUES
(1, 17, 27, 462000.00, 43, 0, 'cash', '2025-04-13 08:54:47', 462000.00, '2025-04-13 01:54:47', '2025-04-13 01:54:47'),
(2, 17, 28, 312000.00, 28, 0, 'credit_card', '2025-04-13 08:57:29', 312000.00, '2025-04-13 01:57:29', '2025-04-13 01:57:29'),
(3, 17, 29, 312000.00, 28, 0, 'credit_card', '2025-04-13 10:41:26', 312000.00, '2025-04-13 03:41:26', '2025-04-13 03:41:26'),
(4, 17, 30, 62000.00, 3, 0, 'credit_card', '2025-04-13 10:49:14', 62000.00, '2025-04-13 03:49:14', '2025-04-13 03:49:14'),
(5, 17, 31, 352000.00, 32, 0, 'credit_card', '2025-04-13 11:53:29', 352000.00, '2025-04-13 04:53:29', '2025-04-13 04:53:29'),
(6, 17, 32, 192000.00, 16, 0, 'credit_card', '2025-04-13 11:55:35', 192000.00, '2025-04-13 04:55:35', '2025-04-13 04:55:35'),
(7, 17, 33, 312000.00, 28, 0, 'credit_card', '2025-04-13 12:17:11', 312000.00, '2025-04-13 05:17:11', '2025-04-13 05:17:11'),
(8, 17, 34, 472000.00, 44, 0, 'credit_card', '2025-04-13 15:36:17', 472000.00, '2025-04-13 08:36:17', '2025-04-13 08:36:17'),
(9, 19, 35, 252000.00, 22, 0, 'credit_card', '2025-04-13 17:16:37', 252000.00, '2025-04-13 10:16:37', '2025-04-13 10:16:37'),
(10, 17, 36, 252000.00, 22, 0, 'credit_card', '2025-04-13 17:40:25', 252000.00, '2025-04-13 10:40:25', '2025-04-13 10:40:25');

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

--
-- Đang đổ dữ liệu cho bảng `treatments`
--

INSERT INTO `treatments` (`id`, `name`, `description`, `price`, `duration`, `equipment_needed`, `contraindications`, `side_effects`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Trị liệu làm sạch sâu', 'Làm sạch sâu lỗ chân lông, loại bỏ mụn cám, mụn đầu đen', 850000.00, 60, 'Máy hút mụn, dung dịch làm sạch', 'Da bị tổn thương, viêm nặng, dị ứng', 'Có thể gây đỏ nhẹ tạm thời', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(2, 'Trị liệu trẻ hóa da', 'Kích thích sản sinh collagen, làm mờ nếp nhăn, cải thiện độ đàn hồi', 1500000.00, 90, 'Máy RF, máy ánh sáng', 'Mang thai, có bệnh tim, da bị viêm nhiễm', 'Có thể gây đỏ, tê nhẹ', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(3, 'Điều trị mụn chuyên sâu', 'Điều trị mụn nang, mụn bọc, mụn viêm nặng', 1200000.00, 75, 'Máy điện di, tia laser, dung dịch điều trị', 'Da bị tổn thương nặng, dị ứng thuốc', 'Có thể gây đỏ, bong tróc nhẹ', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(4, 'Điều trị sẹo rỗ', 'Cải thiện sẹo rỗ do mụn, làm phẳng bề mặt da', 2500000.00, 120, 'Máy laser fractional, kim lăn', 'Mang thai, da đang viêm nhiễm, bệnh tự miễn', 'Đỏ, sưng, có thể có vảy nhỏ trong vài ngày', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(5, 'Trị nám, tàn nhang', 'Làm mờ vết nám, tàn nhang, đốm nâu', 1800000.00, 90, 'Laser Q-Switched, máy IPL', 'Da cháy nắng, mang thai, dùng thuốc nhạy sáng', 'Có thể gây sưng đỏ, bong tróc nhẹ', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(6, 'Điều trị rosacea', 'Giảm đỏ, viêm cho da bị rosacea', 1300000.00, 60, 'Laser mạch máu, dung dịch làm dịu', 'Dị ứng với thành phần sản phẩm, viêm da cấp tính', 'Có thể gây đỏ nhẹ tạm thời', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(7, 'Trẻ hóa vùng mắt', 'Giảm bọng mắt, quầng thâm, nếp nhăn vùng mắt', 1100000.00, 45, 'Máy RF vùng mắt, serum chuyên biệt', 'Viêm kết mạc, sau phẫu thuật mắt', 'Có thể gây sưng nhẹ', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL),
(8, 'Massage da mặt thư giãn', 'Thư giãn, cải thiện tuần hoàn, giảm căng thẳng trên da', 600000.00, 60, 'Dầu massage, đá nóng', 'Da đang viêm nhiễm, mụn trứng cá nặng', 'Hiếm khi xảy ra, có thể gây đỏ nhẹ', '2025-04-06 09:41:45', '2025-04-06 09:41:45', NULL);

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
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `total_spent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `last_transaction` datetime DEFAULT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `failed_appointments` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','temporary_locked','permanent_locked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id_user`, `id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `provider`, `provider_id`, `age`, `gender`, `address`, `specialization`, `points`, `total_spent`, `last_transaction`, `email_verification_token`, `avatar`, `email_verified_at`, `remember_token`, `failed_appointments`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 1, 'Quản trị viên', 'admin@example.com', '0901234567', '$2y$10$qG0XjfwOH1Yg/JHfjAe8oeLpE3jB5CK7wANDyZ4JFF5nBlMZQeBLK', NULL, NULL, NULL, NULL, 'Quận 1, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:44', NULL, 0, '2025-04-06 09:39:39', '2025-04-06 09:41:44', 'active'),
(2, 2, 1, 'Bác sĩ Nguyễn Văn A', 'doctor1@example.com', '0911111111', '$2y$10$K1lskx1D1zSxUNF3eOgYQ.oPvVy0qDpmzDiEQLFo3VIZUlGNk27hC', NULL, NULL, NULL, NULL, 'Quận 3, TP. Hồ Chí Minh', 'Da liễu', 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:44', NULL, 0, '2025-04-06 09:41:44', '2025-04-09 01:13:28', 'active'),
(3, 2, 1, 'Bác sĩ Lê Thị B', 'doctor2@example.com', '0922222222', '$2y$10$FuU.A0a2v/wXDEH9M7m70.kgPKNHm/RzumaIYPx8U16a0pkvmdP72', NULL, NULL, NULL, NULL, 'Quận 5, TP. Hồ Chí Minh', 'Thẩm mỹ', 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:44', NULL, 0, '2025-04-06 09:41:44', '2025-04-06 09:41:44', 'active'),
(4, 2, 1, 'Bác sĩ Trần Văn C', 'doctor3@example.com', '0933333333', '$2y$10$IvjElBxijnj9KwAi3yf2WuTZZvxvMo.s9CUvgtfqM7PNEDqdmB7Q.', NULL, NULL, NULL, NULL, 'Quận 7, TP. Hồ Chí Minh', 'Phẫu thuật thẩm mỹ', 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(5, 3, 1, 'Dược sĩ Phạm Thị D', 'pharmacist1@example.com', '0944444444', '$2y$10$s47ffgXHDV2/.kM.eQ4M9uQ.uGBpCnugQswFX3jCd90F9Dthrbx4e', NULL, NULL, NULL, NULL, 'Quận 4, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(6, 3, 1, 'Dược sĩ Võ Văn E', 'pharmacist2@example.com', '0955555555', '$2y$10$b7HL.V2vJHmncqphI793T.X7qooiImKhVy52cciKF8sra8jP2FyYW', NULL, NULL, NULL, NULL, 'Quận 6, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(7, 4, 1, 'Khách hàng 1', 'customer1@example.com', '091111111', '$2y$10$GThRy9G48cE3O/5NtrLj9.3Sam15NZryhcBdXNoXcPQ5MXxLwAC8C', NULL, NULL, 43, 'female', 'Địa chỉ khách hàng 1, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-11 06:38:12', 'active'),
(8, 4, 1, 'Khách hàng 2', 'customer2@example.com', '092222222', '$2y$10$YDVUI5wQhixkruvwj78cKuwyvnSsV3oG7/4Qqtunh4OGJI1MQfm.2', NULL, NULL, 30, 'other', 'Địa chỉ khách hàng 2, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(9, 4, 1, 'Khách hàng 3', 'customer3@example.com', '093333333', '$2y$10$i7juNHxldoQjYwKo5YYxze3PmRa5AF4n41bYF9Vrjf1xtvITHU06K', NULL, NULL, 46, 'female', 'Địa chỉ khách hàng 3, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(10, 4, 1, 'Khách hàng 4', 'customer4@example.com', '094444444', '$2y$10$20D/yVstvO/RhXsdAISjoOjvkzdob8fulwh8eEEI4p9/i/OPrJGAe', NULL, NULL, 19, 'female', 'Địa chỉ khách hàng 4, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(11, 4, 1, 'Khách hàng 5', 'customer5@example.com', '095555555', '$2y$10$j2.t1NQl5YGyzANb6VpkM.r/BevKGJ6D9tsSB/2864m5Q1ILb4PdG', NULL, NULL, 23, 'male', 'Địa chỉ khách hàng 5, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(12, 4, 1, 'Khách hàng 6', 'customer6@example.com', '096666666', '$2y$10$AzNOQEMrGstkVYOP1f/ivOAZOTlQ.srbs9odYt8BCp/W6Wo7rVu2C', NULL, NULL, 26, 'female', 'Địa chỉ khách hàng 6, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(13, 4, 1, 'Khách hàng 7', 'customer7@example.com', '097777777', '$2y$10$Tb9bYg322d9X8nykM9zNOOn68yVEv9Od.JQriZVeH3JwRDEEr6Q46', NULL, NULL, 60, 'female', 'Địa chỉ khách hàng 7, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(14, 4, 1, 'Khách hàng 8', 'customer8@example.com', '098888888', '$2y$10$H4jKfyWv82n2FdlzgRKAGu75teJylvhwVmz/xC9tl.a22F9J4rhCW', NULL, NULL, 25, 'female', 'Địa chỉ khách hàng 8, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(15, 4, 1, 'Khách hàng 9', 'customer9@example.com', '099999999', '$2y$10$qoDtdqqK50h4b1KuOih8GeafHweqOsojY2nkBD5dyBgWMkLIqteYa', NULL, NULL, 24, 'other', 'Địa chỉ khách hàng 9, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(16, 4, 1, 'Khách hàng 10', 'customer10@example.com', '0910101010101010', '$2y$10$HsOlzWw851S0PwIUyLlxRuCmFd.P66Acsq/qg3NlF7shENHGnP2/q', NULL, NULL, 34, 'female', 'Địa chỉ khách hàng 10, TP. Hồ Chí Minh', NULL, 0, 0.00, NULL, NULL, NULL, '2025-04-06 09:41:45', NULL, 0, '2025-04-06 09:41:45', '2025-04-06 09:41:45', 'active'),
(17, 4, 1, 'A Zăn B', 'abcde@gmail.com', '0352703821', '$2y$10$edjA/d4Ubxnz/H9ei0LW2.GHz/vcTczTdlqtHnSCTXdI.rUvksGZa', NULL, NULL, 1, NULL, NULL, NULL, 0, 0.00, NULL, 'meZHXEiUCjTX2kRSOPD2AnwLmRuu8BQKnagvRx0zCzRZlTX2BCAdly9JT9xW0wJF', 'avatars/1744559498_ChatGPT Image Apr 12, 2025, 07_30_10 AM.png', '2025-04-11 06:44:08', '4nVTGO3URR41XaXJJtUVjLr4us1W4ULrd6wFe3MPkzgool4cjfXBgqG97T7C', 0, '2025-04-11 06:44:08', '2025-04-13 08:51:38', 'active'),
(18, 4, 1, '8070_Thái Hưng Thịnh', 'hthin217@gmail.com', '', '$2y$10$BGqZS5IRd0foHAS2lSpered6SQTxWvfohyHAAKaSsKWOJ9T2lEFNe', 'google', '112693309858835819541', NULL, NULL, NULL, NULL, 0, 0.00, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocIiaxdzad2tCxsC6jw6u3Z7IXJmXMYICLxJyKb7v35omWKlYkO_=s96-c', NULL, NULL, 0, '2025-04-13 10:00:27', '2025-04-13 10:00:27', 'active'),
(19, 4, 1, 'Thai Hung Thinh', 'hthinpbe@gmail.com', '', '$2y$10$LYV8HF4n1Mats4OH5uVAPeaTbriwhKbz3jCFgMJxiCAvGCksHlpPS', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0.00, NULL, 'ddc7CAzWHWjbR02vHWK6Ehq5aqGLE1KPblLJWVbcaEM0Zb5jWAz0sdT8fiLUHlBw', NULL, '2025-04-13 10:11:34', 'DZVAdoj4ziQEBEy9boyqzVopv8R9zH65UPiLLgLzJzDCYRIfYl7sK4zM9wu8', 0, '2025-04-13 10:11:34', '2025-04-13 10:11:34', 'active');

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
-- Chỉ mục cho bảng `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_schedules_date_is_available_index` (`date`,`is_available`),
  ADD KEY `doctor_schedules_doctor_id_date_index` (`doctor_id`,`date`),
  ADD KEY `doctor_schedules_repeat_weekly_index` (`repeat_weekly`);

--
-- Chỉ mục cho bảng `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id_inventory`),
  ADD KEY `inventory_id_cosmetic_foreign` (`id_cosmetic`);

--
-- Chỉ mục cho bảng `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_logs_medicine_id_foreign` (`medicine_id`),
  ADD KEY `inventory_logs_user_id_foreign` (`user_id`);

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
  ADD KEY `fk__prescriptions_id_medical_records` (`id_medical_record`),
  ADD KEY `prescriptions_id_patient_foreign` (`id_patient`),
  ADD KEY `prescriptions_id_doctor_foreign` (`id_doctor`),
  ADD KEY `prescriptions_processed_by_foreign` (`processed_by`);

--
-- Chỉ mục cho bảng `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_items_prescription_id_foreign` (`prescription_id`),
  ADD KEY `prescription_items_medicine_id_foreign` (`medicine_id`);

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
-- Chỉ mục cho bảng `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id_appointment` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `cosmetics`
--
ALTER TABLE `cosmetics`
  MODIFY `id_cosmetic` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id_inventory` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id_medical_record` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id_prescription` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id_review` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id_service` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `ships`
--
ALTER TABLE `ships`
  MODIFY `id_ship` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id_transaction` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
-- Các ràng buộc cho bảng `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_id_cosmetic_foreign` FOREIGN KEY (`id_cosmetic`) REFERENCES `cosmetics` (`id_cosmetic`);

--
-- Các ràng buộc cho bảng `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `inventory_logs_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `prescriptions_id_doctor_foreign` FOREIGN KEY (`id_doctor`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `prescriptions_id_medical_record_foreign` FOREIGN KEY (`id_medical_record`) REFERENCES `medical_records` (`id_medical_record`),
  ADD CONSTRAINT `prescriptions_id_patient_foreign` FOREIGN KEY (`id_patient`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `prescriptions_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id_user`);

--
-- Các ràng buộc cho bảng `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`),
  ADD CONSTRAINT `prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id_prescription`) ON DELETE CASCADE;

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
