<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo"> <span style="font-size: 48px; vertical-align: middle; margin: 0 20px;">+</span> <span style="font-size: 48px; vertical-align: middle;">O2Skin</span></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Phòng Khám O2Skin - Hệ Thống Quản Lý Phòng Khám

Hệ thống quản lý phòng khám O2Skin là một ứng dụng web được phát triển bằng Laravel, giúp tin học hóa quy trình quản lý bệnh nhân, lịch hẹn và dịch vụ trong phòng khám.

## Tính Năng Chính

### Dành Cho Bệnh Nhân
- Đăng ký và đăng nhập tài khoản
- Đặt lịch khám và chọn bác sĩ
- Xem lịch sử khám bệnh
- Thanh toán trực tuyến
- Xem và mua sản phẩm từ cửa hàng

### Dành Cho Bác Sĩ
- Quản lý lịch làm việc
- Xem danh sách bệnh nhân và lịch hẹn
- Quản lý hồ sơ bệnh án
- Kê đơn thuốc

### Dành Cho Quản Trị Viên
- Quản lý nhân viên và bác sĩ
- Báo cáo doanh thu và thống kê
- Quản lý dịch vụ và giá cả
- Quản lý kho thuốc và sản phẩm

### Dành Cho Dược Sĩ
- Quản lý đơn thuốc
- Quản lý kho thuốc
- Xử lý đơn hàng sản phẩm

## Yêu Cầu Hệ Thống

- PHP >= 8.1
- Composer
- MySQL hoặc MariaDB
- Node.js và NPM

## Cài Đặt và Chạy Ứng Dụng

1. Clone dự án:
```bash
git clone https://github.com/yourusername/o2skin-clinic.git
cd o2skin-clinic
```

2. Cài đặt các dependencies:
```bash
composer install
npm install
```

3. Tạo file .env và cấu hình cơ sở dữ liệu:
```bash
cp .env.example .env
php artisan key:generate
```

4. Chỉnh sửa file .env với thông tin kết nối database

5. Chạy migration và seeder:
```bash
php artisan migrate --seed
```

6. Biên dịch assets:
```bash
npm run dev
```

7. Chạy ứng dụng:
```bash
php artisan serve
```

Ứng dụng sẽ chạy tại địa chỉ http://localhost:8000

## Tài Khoản Demo

| Vai trò     | Email                  | Mật khẩu  |
|-------------|------------------------|-----------|
| Admin       | admin@o2skin.com       | password  |
| Bác sĩ      | doctor@o2skin.com      | password  |
| Dược sĩ     | pharmacist@o2skin.com  | password  |
| Bệnh nhân   | patient@example.com    | password  |

## Đóng Góp

Nếu bạn muốn đóng góp cho dự án, vui lòng tạo pull request hoặc báo cáo lỗi trong mục Issues.

## Giấy Phép

Dự án này được cấp phép theo [MIT license](https://opensource.org/licenses/MIT).
