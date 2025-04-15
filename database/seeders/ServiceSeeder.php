<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        $services = [
            [
                'name' => 'Khám tổng quát',
                'description' => 'Dịch vụ khám sức khỏe tổng quát toàn diện, bao gồm kiểm tra các chỉ số cơ bản, huyết áp, tim mạch và tư vấn sức khỏe.',
                'price' => 500000.00,
                'duration' => 45,
                'image' => 'user/theme/images/products/service-1.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khám da liễu',
                'description' => 'Dịch vụ khám và điều trị các vấn đề về da, bao gồm mụn trứng cá, viêm da, dị ứng da và các bệnh lý da liễu khác.',
                'price' => 400000.00,
                'duration' => 30,
                'image' => 'user/theme/images/products/service-2.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khám nha khoa',
                'description' => 'Dịch vụ khám, làm sạch răng và tư vấn về sức khỏe răng miệng.',
                'price' => 350000.00,
                'duration' => 40,
                'image' => 'user/theme/images/products/service-3.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Xét nghiệm máu',
                'description' => 'Dịch vụ xét nghiệm máu toàn diện, kiểm tra chỉ số đường huyết, mỡ máu và các chỉ số quan trọng khác.',
                'price' => 250000.00,
                'duration' => 15,
                'image' => 'user/theme/images/products/service-4.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Siêu âm ổ bụng',
                'description' => 'Dịch vụ siêu âm kiểm tra các cơ quan nội tạng trong ổ bụng như gan, thận, túi mật.',
                'price' => 450000.00,
                'duration' => 20,
                'image' => 'user/theme/images/products/service-6.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khám tim mạch',
                'description' => 'Dịch vụ khám chuyên sâu về tim mạch, bao gồm đo điện tâm đồ và tư vấn phòng ngừa bệnh tim mạch.',
                'price' => 600000.00,
                'duration' => 35,
                'image' => 'user/theme/images/products/service-8.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khám mắt',
                'description' => 'Dịch vụ khám thị lực, kiểm tra sức khỏe mắt và tư vấn các vấn đề về thị giác.',
                'price' => 300000.00,
                'duration' => 25,
                'image' => 'user/theme/images/products/service-1.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Châm cứu',
                'description' => 'Dịch vụ châm cứu điều trị đau nhức, cải thiện tuần hoàn máu và phục hồi sức khỏe.',
                'price' => 350000.00,
                'duration' => 40,
                'image' => 'user/theme/images/products/service-2.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Vật lý trị liệu',
                'description' => 'Dịch vụ vật lý trị liệu giúp phục hồi chức năng vận động, giảm đau và tăng cường sức khỏe cơ xương khớp.',
                'price' => 400000.00,
                'duration' => 45,
                'image' => 'user/theme/images/products/service-3.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tư vấn dinh dưỡng',
                'description' => 'Dịch vụ tư vấn chế độ dinh dưỡng, xây dựng thực đơn phù hợp với tình trạng sức khỏe của từng cá nhân.',
                'price' => 300000.00,
                'duration' => 30,
                'image' => 'user/theme/images/products/service-4.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        DB::table('services')->insert($services);
    }
}
