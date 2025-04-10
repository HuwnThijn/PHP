-- Dữ liệu mẫu cho bảng categories
INSERT INTO `categories` (`id_category`, `name`, `created_at`, `updated_at`) VALUES
(1, 'La Beauty', NOW(), NOW()),
(2, 'Fixderma', NOW(), NOW()),
(3, 'GAMMA', NOW(), NOW()),
(4, 'On: The Body', NOW(), NOW()),
(5, 'Laroche posay', NOW(), NOW());

-- Dữ liệu mẫu cho bảng cosmetics
INSERT INTO `cosmetics` (`id_cosmetic`, `id_category`, `name`, `price`, `rating`, `isHidden`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sữa tắm gội em bé Gentle Wash 500ml La Beauty', 159000, 4.5, 0, 'user/theme/images/products/product-1.jpg', NOW(), NOW()),
(2, 2, 'Gel chấm mụn, mờ thâm Decumar Advance THC 20g', 105000, 4.0, 0, 'user/theme/images/products/product-2.jpg', NOW(), NOW()),
(3, 3, 'Dung dịch vệ sinh vùng kín Bimunica 250ml', 184000, 4.8, 0, 'user/theme/images/products/product-3.jpg', NOW(), NOW()),
(4, 1, 'Dầu Dừa Tươi Raw Virgin Coconut Oil Coboté 50ml', 81000, 4.2, 0, 'user/theme/images/products/product-4.jpg', NOW(), NOW()),
(5, 2, 'Kem giảm thâm vùng nách, mông, bikini Neotherica Armpil Cream', 139000, 3.9, 0, 'user/theme/images/products/product-5.jpg', NOW(), NOW()),
(6, 3, 'Gel rửa mặt SVR Sebiaclear Gel Moussant 200ml', 390000, 4.7, 0, 'user/theme/images/products/product-6.jpg', NOW(), NOW()),
(7, 4, 'Bọt vệ sinh nam giới Sumely Mens Sanitary Foam', 96000, 4.1, 0, 'user/theme/images/products/product-7.jpg', NOW(), NOW()),
(8, 4, 'Sữa rửa mặt On: The Body Rice Therapy Heartleaf Acne Cleanser', 165000, 4.3, 0, 'user/theme/images/products/product-8.jpg', NOW(), NOW()),
(9, 5, 'Kem chống nắng La Roche-Posay Anthelios UVMune 400 SPF50+', 450000, 4.9, 0, 'user/theme/images/products/product-9.jpg', NOW(), NOW()),
(10, 5, 'Nước tẩy trang La Roche-Posay Micellar Water Ultra', 395000, 4.6, 0, 'user/theme/images/products/product-10.jpg', NOW(), NOW()),
(11, 1, 'Mặt nạ dưỡng ẩm La Beauty Hydrating Mask', 75000, 4.0, 0, 'user/theme/images/products/product-11.jpg', NOW(), NOW()),
(12, 2, 'Serum trị mụn Fixderma Acne Clear Solution', 220000, 4.4, 0, 'user/theme/images/products/product-12.jpg', NOW(), NOW()); 