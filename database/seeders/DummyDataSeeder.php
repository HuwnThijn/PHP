<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use App\Models\Medicine;
use App\Models\Treatment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo các bảng roles và ranks đã có dữ liệu
        $this->seedRolesAndRanks();

        // Tạo người dùng mẫu
        $this->seedUsers();

        // Tạo thuốc mẫu
        $this->seedMedicines();

        // Tạo trị liệu mẫu
        $this->seedTreatments();
    }

    /**
     * Seed roles và ranks nếu chưa có
     */
    private function seedRolesAndRanks(): void
    {
        // Tạo roles nếu chưa có
        $roles = [
            ['name' => 'admin'],
            ['name' => 'doctor'],
            ['name' => 'pharmacist'],
            ['name' => 'customer'],
        ];
        
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
        
        // Tạo ranks nếu chưa có
        $ranks = [
            ['name' => 'bronze', 'min_points' => 0],
            ['name' => 'silver', 'min_points' => 100],
            ['name' => 'gold', 'min_points' => 500],
            ['name' => 'platinum', 'min_points' => 1000],
        ];
        
        foreach ($ranks as $rank) {
            Rank::updateOrCreate(
                ['name' => $rank['name']],
                $rank
            );
        }
    }

    /**
     * Seed users mẫu
     */
    private function seedUsers(): void
    {
        // Lấy các roles
        $adminRole = Role::where('name', 'admin')->first();
        $doctorRole = Role::where('name', 'doctor')->first();
        $pharmacistRole = Role::where('name', 'pharmacist')->first();
        $customerRole = Role::where('name', 'customer')->first();
        
        // Lấy rank mặc định (bronze)
        $defaultRank = Rank::where('name', 'bronze')->first();
        
        // Tạo 1 admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'id_role' => $adminRole->id_role,
                'id_rank' => $defaultRank->id_rank,
                'name' => 'Quản trị viên',
                'phone' => '0901234567',
                'address' => 'Quận 1, TP. Hồ Chí Minh',
                'password' => Hash::make('password'),
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now()
            ]
        );
        
        // Tạo 3 bác sĩ
        $doctors = [
            [
                'email' => 'doctor1@example.com',
                'name' => 'Bác sĩ Nguyễn Văn A',
                'phone' => '0911111111',
                'address' => 'Quận 3, TP. Hồ Chí Minh',
                'specialization' => 'Da liễu'
            ],
            [
                'email' => 'doctor2@example.com',
                'name' => 'Bác sĩ Lê Thị B',
                'phone' => '0922222222',
                'address' => 'Quận 5, TP. Hồ Chí Minh',
                'specialization' => 'Thẩm mỹ'
            ],
            [
                'email' => 'doctor3@example.com',
                'name' => 'Bác sĩ Trần Văn C',
                'phone' => '0933333333',
                'address' => 'Quận 7, TP. Hồ Chí Minh',
                'specialization' => 'Phẫu thuật thẩm mỹ'
            ]
        ];
        
        foreach ($doctors as $doctor) {
            User::updateOrCreate(
                ['email' => $doctor['email']],
                [
                    'id_role' => $doctorRole->id_role,
                    'id_rank' => $defaultRank->id_rank,
                    'name' => $doctor['name'],
                    'phone' => $doctor['phone'],
                    'address' => $doctor['address'],
                    'specialization' => $doctor['specialization'],
                    'password' => Hash::make('password'),
                    'status' => User::STATUS_ACTIVE,
                    'email_verified_at' => now()
                ]
            );
        }
        
        // Tạo 2 dược sĩ
        $pharmacists = [
            [
                'email' => 'pharmacist1@example.com',
                'name' => 'Dược sĩ Phạm Thị D',
                'phone' => '0944444444',
                'address' => 'Quận 4, TP. Hồ Chí Minh'
            ],
            [
                'email' => 'pharmacist2@example.com',
                'name' => 'Dược sĩ Võ Văn E',
                'phone' => '0955555555',
                'address' => 'Quận 6, TP. Hồ Chí Minh'
            ]
        ];
        
        foreach ($pharmacists as $pharmacist) {
            User::updateOrCreate(
                ['email' => $pharmacist['email']],
                [
                    'id_role' => $pharmacistRole->id_role,
                    'id_rank' => $defaultRank->id_rank,
                    'name' => $pharmacist['name'],
                    'phone' => $pharmacist['phone'],
                    'address' => $pharmacist['address'],
                    'password' => Hash::make('password'),
                    'status' => User::STATUS_ACTIVE,
                    'email_verified_at' => now()
                ]
            );
        }
        
        // Tạo 10 khách hàng
        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                ['email' => "customer{$i}@example.com"],
                [
                    'id_role' => $customerRole->id_role,
                    'id_rank' => $defaultRank->id_rank,
                    'name' => "Khách hàng {$i}",
                    'phone' => "09{$i}{$i}{$i}{$i}{$i}{$i}{$i}",
                    'address' => "Địa chỉ khách hàng {$i}, TP. Hồ Chí Minh",
                    'password' => Hash::make('password'),
                    'status' => User::STATUS_ACTIVE,
                    'email_verified_at' => now(),
                    'age' => rand(18, 60),
                    'gender' => ['male', 'female', 'other'][rand(0, 2)]
                ]
            );
        }
    }
    
    /**
     * Seed thuốc mẫu
     */
    private function seedMedicines(): void
    {
        $medicines = [
            [
                'name' => 'Retinol Serum',
                'description' => 'Serum chứa Retinol giúp làm mờ nếp nhăn, cải thiện kết cấu da',
                'price' => 550000,
                'stock_quantity' => 50,
                'manufacturer' => 'The Ordinary',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Serum',
                'usage_instructions' => 'Sử dụng vào buổi tối, tránh tiếp xúc với ánh nắng trực tiếp'
            ],
            [
                'name' => 'Vitamin C Serum',
                'description' => 'Serum Vitamin C giúp làm sáng da, chống oxy hóa',
                'price' => 650000,
                'stock_quantity' => 40,
                'manufacturer' => 'SkinCeuticals',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Serum',
                'usage_instructions' => 'Sử dụng vào buổi sáng, kết hợp với kem chống nắng'
            ],
            [
                'name' => 'Hyaluronic Acid',
                'description' => 'Cấp ẩm sâu cho da, giúp da căng mọng',
                'price' => 450000,
                'stock_quantity' => 60,
                'manufacturer' => 'La Roche-Posay',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Serum',
                'usage_instructions' => 'Sử dụng sáng và tối trên da ẩm'
            ],
            [
                'name' => 'Niacinamide',
                'description' => 'Giảm mụn, thu nhỏ lỗ chân lông, cân bằng dầu',
                'price' => 350000,
                'stock_quantity' => 45,
                'manufacturer' => 'The Ordinary',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Serum',
                'usage_instructions' => 'Sử dụng sáng và tối sau khi rửa mặt'
            ],
            [
                'name' => 'Salicylic Acid',
                'description' => 'Loại bỏ tế bào chết, thông thoáng lỗ chân lông, giảm mụn',
                'price' => 380000,
                'stock_quantity' => 35,
                'manufacturer' => 'Paula\'s Choice',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Dung dịch',
                'usage_instructions' => 'Sử dụng 1-2 lần/tuần vào buổi tối'
            ],
            [
                'name' => 'Kem dưỡng ẩm Ceramide',
                'description' => 'Phục hồi hàng rào bảo vệ da, cấp ẩm sâu',
                'price' => 480000,
                'stock_quantity' => 30,
                'manufacturer' => 'CeraVe',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Kem',
                'usage_instructions' => 'Sử dụng sáng và tối sau serum'
            ],
            [
                'name' => 'Kem chống nắng SPF 50',
                'description' => 'Bảo vệ da khỏi tia UVA/UVB, ngăn ngừa lão hóa sớm',
                'price' => 420000,
                'stock_quantity' => 55,
                'manufacturer' => 'La Roche-Posay',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Kem',
                'usage_instructions' => 'Sử dụng mỗi sáng, thoa lại sau 2 giờ nếu tiếp xúc với ánh nắng'
            ],
            [
                'name' => 'Gel trị mụn Benzoyl Peroxide',
                'description' => 'Tiêu diệt vi khuẩn gây mụn, giảm viêm',
                'price' => 320000,
                'stock_quantity' => 40,
                'manufacturer' => 'Effaclar',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Gel',
                'usage_instructions' => 'Thoa lên vùng mụn vào buổi tối'
            ],
            [
                'name' => 'Mặt nạ làm dịu Aloe Vera',
                'description' => 'Làm dịu và cấp ẩm cho da kích ứng',
                'price' => 180000,
                'stock_quantity' => 70,
                'manufacturer' => 'Innisfree',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Mặt nạ',
                'usage_instructions' => 'Đắp 15-20 phút, 1-2 lần/tuần'
            ],
            [
                'name' => 'Tẩy tế bào chết AHA/BHA',
                'description' => 'Loại bỏ tế bào chết, làm sáng da, giảm mụn ẩn',
                'price' => 520000,
                'stock_quantity' => 25,
                'manufacturer' => 'Cosrx',
                'expiry_date' => Carbon::now()->addYear(),
                'dosage_form' => 'Dung dịch',
                'usage_instructions' => 'Sử dụng 1-2 lần/tuần vào buổi tối'
            ]
        ];
        
        foreach ($medicines as $medicine) {
            Medicine::updateOrCreate(
                ['name' => $medicine['name']],
                $medicine
            );
        }
    }
    
    /**
     * Seed trị liệu mẫu
     */
    private function seedTreatments(): void
    {
        $treatments = [
            [
                'name' => 'Trị liệu làm sạch sâu',
                'description' => 'Làm sạch sâu lỗ chân lông, loại bỏ mụn cám, mụn đầu đen',
                'price' => 850000,
                'duration' => 60,
                'equipment_needed' => 'Máy hút mụn, dung dịch làm sạch',
                'contraindications' => 'Da bị tổn thương, viêm nặng, dị ứng',
                'side_effects' => 'Có thể gây đỏ nhẹ tạm thời'
            ],
            [
                'name' => 'Trị liệu trẻ hóa da',
                'description' => 'Kích thích sản sinh collagen, làm mờ nếp nhăn, cải thiện độ đàn hồi',
                'price' => 1500000,
                'duration' => 90,
                'equipment_needed' => 'Máy RF, máy ánh sáng',
                'contraindications' => 'Mang thai, có bệnh tim, da bị viêm nhiễm',
                'side_effects' => 'Có thể gây đỏ, tê nhẹ'
            ],
            [
                'name' => 'Điều trị mụn chuyên sâu',
                'description' => 'Điều trị mụn nang, mụn bọc, mụn viêm nặng',
                'price' => 1200000,
                'duration' => 75,
                'equipment_needed' => 'Máy điện di, tia laser, dung dịch điều trị',
                'contraindications' => 'Da bị tổn thương nặng, dị ứng thuốc',
                'side_effects' => 'Có thể gây đỏ, bong tróc nhẹ'
            ],
            [
                'name' => 'Điều trị sẹo rỗ',
                'description' => 'Cải thiện sẹo rỗ do mụn, làm phẳng bề mặt da',
                'price' => 2500000,
                'duration' => 120,
                'equipment_needed' => 'Máy laser fractional, kim lăn',
                'contraindications' => 'Mang thai, da đang viêm nhiễm, bệnh tự miễn',
                'side_effects' => 'Đỏ, sưng, có thể có vảy nhỏ trong vài ngày'
            ],
            [
                'name' => 'Trị nám, tàn nhang',
                'description' => 'Làm mờ vết nám, tàn nhang, đốm nâu',
                'price' => 1800000,
                'duration' => 90,
                'equipment_needed' => 'Laser Q-Switched, máy IPL',
                'contraindications' => 'Da cháy nắng, mang thai, dùng thuốc nhạy sáng',
                'side_effects' => 'Có thể gây sưng đỏ, bong tróc nhẹ'
            ],
            [
                'name' => 'Điều trị rosacea',
                'description' => 'Giảm đỏ, viêm cho da bị rosacea',
                'price' => 1300000,
                'duration' => 60,
                'equipment_needed' => 'Laser mạch máu, dung dịch làm dịu',
                'contraindications' => 'Dị ứng với thành phần sản phẩm, viêm da cấp tính',
                'side_effects' => 'Có thể gây đỏ nhẹ tạm thời'
            ],
            [
                'name' => 'Trẻ hóa vùng mắt',
                'description' => 'Giảm bọng mắt, quầng thâm, nếp nhăn vùng mắt',
                'price' => 1100000,
                'duration' => 45,
                'equipment_needed' => 'Máy RF vùng mắt, serum chuyên biệt',
                'contraindications' => 'Viêm kết mạc, sau phẫu thuật mắt',
                'side_effects' => 'Có thể gây sưng nhẹ'
            ],
            [
                'name' => 'Massage da mặt thư giãn',
                'description' => 'Thư giãn, cải thiện tuần hoàn, giảm căng thẳng trên da',
                'price' => 600000,
                'duration' => 60,
                'equipment_needed' => 'Dầu massage, đá nóng',
                'contraindications' => 'Da đang viêm nhiễm, mụn trứng cá nặng',
                'side_effects' => 'Hiếm khi xảy ra, có thể gây đỏ nhẹ'
            ]
        ];
        
        foreach ($treatments as $treatment) {
            Treatment::updateOrCreate(
                ['name' => $treatment['name']],
                $treatment
            );
        }
    }
} 