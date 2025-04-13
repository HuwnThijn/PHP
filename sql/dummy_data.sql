-- Tạo roles nếu chưa có
INSERT INTO `roles` (`name`, `created_at`, `updated_at`) VALUES
('admin', NOW(), NOW()),
('doctor', NOW(), NOW()),
('pharmacist', NOW(), NOW()),
('customer', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Tạo ranks nếu chưa có
INSERT INTO `ranks` (`name`, `min_points`, `created_at`, `updated_at`) VALUES
('bronze', 0, NOW(), NOW()),
('silver', 100, NOW(), NOW()),
('gold', 500, NOW(), NOW()),
('platinum', 1000, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `min_points` = VALUES(`min_points`);

-- Tạo 1 admin 
INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'admin'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    'Quản trị viên', 
    'admin@example.com', 
    '0901234567', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'Quận 1, TP. Hồ Chí Minh', 
    'active', 
    NOW(), 
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Tạo 3 bác sĩ
INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `specialization`, `status`, `email_verified_at`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'doctor'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    'Bác sĩ Nguyễn Văn A', 
    'doctor1@example.com', 
    '0911111111', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'Quận 3, TP. Hồ Chí Minh',
    'Da liễu',
    'active', 
    NOW(), 
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `specialization`, `status`, `email_verified_at`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'doctor'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    'Bác sĩ Lê Thị B', 
    'doctor2@example.com', 
    '0922222222', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'Quận 5, TP. Hồ Chí Minh',
    'Thẩm mỹ',
    'active', 
    NOW(), 
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `specialization`, `status`, `email_verified_at`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'doctor'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    'Bác sĩ Trần Văn C', 
    'doctor3@example.com', 
    '0933333333', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'Quận 7, TP. Hồ Chí Minh',
    'Phẫu thuật thẩm mỹ',
    'active', 
    NOW(), 
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Tạo 2 dược sĩ
INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'pharmacist'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    'Dược sĩ Phạm Thị D', 
    'pharmacist1@example.com', 
    '0944444444', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'Quận 4, TP. Hồ Chí Minh', 
    'active', 
    NOW(), 
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'pharmacist'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    'Dược sĩ Võ Văn E', 
    'pharmacist2@example.com', 
    '0955555555', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'Quận 6, TP. Hồ Chí Minh', 
    'active', 
    NOW(), 
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Tạo 10 khách hàng
INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 1), 
    'customer1@example.com', 
    '0911111111', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 1, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 2), 
    'customer2@example.com', 
    '0922222222', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 2, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 3), 
    'customer3@example.com', 
    '0933333333', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 3, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 4), 
    'customer4@example.com', 
    '0944444444', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 4, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 5), 
    'customer5@example.com', 
    '0955555555', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 5, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 6), 
    'customer6@example.com', 
    '0966666666', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 6, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 7), 
    'customer7@example.com', 
    '0977777777', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 7, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 8), 
    'customer8@example.com', 
    '0988888888', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 8, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 9), 
    'customer9@example.com', 
    '0999999999', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 9, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

INSERT INTO `users` (`id_role`, `id_rank`, `name`, `email`, `phone`, `password`, `address`, `status`, `email_verified_at`, `age`, `gender`, `created_at`, `updated_at`)
SELECT 
    (SELECT `id_role` FROM `roles` WHERE `name` = 'customer'), 
    (SELECT `id_rank` FROM `ranks` WHERE `name` = 'bronze'), 
    CONCAT('Khách hàng ', 10), 
    'customer10@example.com', 
    '0900000000', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    CONCAT('Địa chỉ khách hàng ', 10, ', TP. Hồ Chí Minh'), 
    'active', 
    NOW(),
    20 + FLOOR(RAND() * 40),
    ELT(1 + FLOOR(RAND() * 3), 'male', 'female', 'other'),
    NOW(), 
    NOW()
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Thêm dữ liệu mẫu cho medicines
INSERT INTO `medicines` (`name`, `description`, `price`, `stock_quantity`, `manufacturer`, `expiry_date`, `dosage_form`, `usage_instructions`, `created_at`, `updated_at`) VALUES
('Retinol Serum', 'Serum chứa Retinol giúp làm mờ nếp nhăn, cải thiện kết cấu da', 550000, 50, 'The Ordinary', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Serum', 'Sử dụng vào buổi tối, tránh tiếp xúc với ánh nắng trực tiếp', NOW(), NOW()),
('Vitamin C Serum', 'Serum Vitamin C giúp làm sáng da, chống oxy hóa', 650000, 40, 'SkinCeuticals', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Serum', 'Sử dụng vào buổi sáng, kết hợp với kem chống nắng', NOW(), NOW()),
('Hyaluronic Acid', 'Cấp ẩm sâu cho da, giúp da căng mọng', 450000, 60, 'La Roche-Posay', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Serum', 'Sử dụng sáng và tối trên da ẩm', NOW(), NOW()),
('Niacinamide', 'Giảm mụn, thu nhỏ lỗ chân lông, cân bằng dầu', 350000, 45, 'The Ordinary', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Serum', 'Sử dụng sáng và tối sau khi rửa mặt', NOW(), NOW()),
('Salicylic Acid', 'Loại bỏ tế bào chết, thông thoáng lỗ chân lông, giảm mụn', 380000, 35, 'Paula\'s Choice', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Dung dịch', 'Sử dụng 1-2 lần/tuần vào buổi tối', NOW(), NOW()),
('Kem dưỡng ẩm Ceramide', 'Phục hồi hàng rào bảo vệ da, cấp ẩm sâu', 480000, 30, 'CeraVe', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Kem', 'Sử dụng sáng và tối sau serum', NOW(), NOW()),
('Kem chống nắng SPF 50', 'Bảo vệ da khỏi tia UVA/UVB, ngăn ngừa lão hóa sớm', 420000, 55, 'La Roche-Posay', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Kem', 'Sử dụng mỗi sáng, thoa lại sau 2 giờ nếu tiếp xúc với ánh nắng', NOW(), NOW()),
('Gel trị mụn Benzoyl Peroxide', 'Tiêu diệt vi khuẩn gây mụn, giảm viêm', 320000, 40, 'Effaclar', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Gel', 'Thoa lên vùng mụn vào buổi tối', NOW(), NOW()),
('Mặt nạ làm dịu Aloe Vera', 'Làm dịu và cấp ẩm cho da kích ứng', 180000, 70, 'Innisfree', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Mặt nạ', 'Đắp 15-20 phút, 1-2 lần/tuần', NOW(), NOW()),
('Tẩy tế bào chết AHA/BHA', 'Loại bỏ tế bào chết, làm sáng da, giảm mụn ẩn', 520000, 25, 'Cosrx', DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Dung dịch', 'Sử dụng 1-2 lần/tuần vào buổi tối', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Thêm dữ liệu mẫu cho treatments
INSERT INTO `treatments` (`name`, `description`, `price`, `duration`, `equipment_needed`, `contraindications`, `side_effects`, `created_at`, `updated_at`) VALUES
('Trị liệu làm sạch sâu', 'Làm sạch sâu lỗ chân lông, loại bỏ mụn cám, mụn đầu đen', 850000, 60, 'Máy hút mụn, dung dịch làm sạch', 'Da bị tổn thương, viêm nặng, dị ứng', 'Có thể gây đỏ nhẹ tạm thời', NOW(), NOW()),
('Trị liệu trẻ hóa da', 'Kích thích sản sinh collagen, làm mờ nếp nhăn, cải thiện độ đàn hồi', 1500000, 90, 'Máy RF, máy ánh sáng', 'Mang thai, có bệnh tim, da bị viêm nhiễm', 'Có thể gây đỏ, tê nhẹ', NOW(), NOW()),
('Điều trị mụn chuyên sâu', 'Điều trị mụn nang, mụn bọc, mụn viêm nặng', 1200000, 75, 'Máy điện di, tia laser, dung dịch điều trị', 'Da bị tổn thương nặng, dị ứng thuốc', 'Có thể gây đỏ, bong tróc nhẹ', NOW(), NOW()),
('Điều trị sẹo rỗ', 'Cải thiện sẹo rỗ do mụn, làm phẳng bề mặt da', 2500000, 120, 'Máy laser fractional, kim lăn', 'Mang thai, da đang viêm nhiễm, bệnh tự miễn', 'Đỏ, sưng, có thể có vảy nhỏ trong vài ngày', NOW(), NOW()),
('Trị nám, tàn nhang', 'Làm mờ vết nám, tàn nhang, đốm nâu', 1800000, 90, 'Laser Q-Switched, máy IPL', 'Da cháy nắng, mang thai, dùng thuốc nhạy sáng', 'Có thể gây sưng đỏ, bong tróc nhẹ', NOW(), NOW()),
('Điều trị rosacea', 'Giảm đỏ, viêm cho da bị rosacea', 1300000, 60, 'Laser mạch máu, dung dịch làm dịu', 'Dị ứng với thành phần sản phẩm, viêm da cấp tính', 'Có thể gây đỏ nhẹ tạm thời', NOW(), NOW()),
('Trẻ hóa vùng mắt', 'Giảm bọng mắt, quầng thâm, nếp nhăn vùng mắt', 1100000, 45, 'Máy RF vùng mắt, serum chuyên biệt', 'Viêm kết mạc, sau phẫu thuật mắt', 'Có thể gây sưng nhẹ', NOW(), NOW()),
('Massage da mặt thư giãn', 'Thư giãn, cải thiện tuần hoàn, giảm căng thẳng trên da', 600000, 60, 'Dầu massage, đá nóng', 'Da đang viêm nhiễm, mụn trứng cá nặng', 'Hiếm khi xảy ra, có thể gây đỏ nhẹ', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`); 