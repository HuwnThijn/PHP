<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cosmetic;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CosmeticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kiểm tra xem có danh mục nào không
        if (Category::count() == 0) {
            // Tạo các danh mục nếu chưa có
            $categories = [
                ['name' => 'Mỹ phẩm dưỡng da'],
                ['name' => 'Mỹ phẩm trang điểm'],
                ['name' => 'Mỹ phẩm chống nắng'],
                ['name' => 'Mỹ phẩm trị mụn'],
                ['name' => 'Mỹ phẩm dưỡng tóc'],
            ];

            foreach ($categories as $category) {
                Category::create($category);
            }
        }

        // Lấy IDs của các danh mục
        $categoryIds = Category::pluck('id_category')->toArray();
        
        // Mảng dữ liệu mỹ phẩm mẫu
        $cosmetics = [
            [
                'name' => 'Kem dưỡng ẩm Cerave',
                'price' => 250000,
                'rating' => 4.5,
                'id_category' => $categoryIds[0], // Mỹ phẩm dưỡng da
                'isHidden' => 0,
                'image' => 'cosmetics/cerave.jpg',
            ],
            [
                'name' => 'Serum The Ordinary Niacinamide',
                'price' => 320000,
                'rating' => 4.8,
                'id_category' => $categoryIds[0], // Mỹ phẩm dưỡng da
                'isHidden' => 0,
                'image' => 'cosmetics/ordinary.jpg',
            ],
            [
                'name' => 'Kem nền Maybelline Fit Me',
                'price' => 180000,
                'rating' => 4.3,
                'id_category' => $categoryIds[1], // Mỹ phẩm trang điểm
                'isHidden' => 0,
                'image' => 'cosmetics/maybelline.jpg',
            ],
            [
                'name' => 'Son MAC Ruby Woo',
                'price' => 550000,
                'rating' => 4.7,
                'id_category' => $categoryIds[1], // Mỹ phẩm trang điểm
                'isHidden' => 0,
                'image' => 'cosmetics/mac.jpg',
            ],
            [
                'name' => 'Kem chống nắng La Roche-Posay',
                'price' => 420000,
                'rating' => 4.9,
                'id_category' => $categoryIds[2], // Mỹ phẩm chống nắng
                'isHidden' => 0,
                'image' => 'cosmetics/laroche.jpg',
            ],
            [
                'name' => 'Xịt khoáng Vichy',
                'price' => 280000,
                'rating' => 4.2,
                'id_category' => $categoryIds[0], // Mỹ phẩm dưỡng da
                'isHidden' => 0,
                'image' => 'cosmetics/vichy.jpg',
            ],
            [
                'name' => 'Gel trị mụn Some By Mi',
                'price' => 350000,
                'rating' => 4.6,
                'id_category' => $categoryIds[3], // Mỹ phẩm trị mụn
                'isHidden' => 0,
                'image' => 'cosmetics/somebymi.jpg',
            ],
            [
                'name' => 'Dầu gội Pantene',
                'price' => 150000,
                'rating' => 4.0,
                'id_category' => $categoryIds[4], // Mỹ phẩm dưỡng tóc
                'isHidden' => 0,
                'image' => 'cosmetics/pantene.jpg',
            ],
            [
                'name' => 'Kem ủ tóc Tresemme',
                'price' => 195000,
                'rating' => 4.2,
                'id_category' => $categoryIds[4], // Mỹ phẩm dưỡng tóc
                'isHidden' => 0,
                'image' => 'cosmetics/tresemme.jpg',
            ],
            [
                'name' => 'Serum trị mụn Tea Tree The Body Shop',
                'price' => 295000,
                'rating' => 4.4,
                'id_category' => $categoryIds[3], // Mỹ phẩm trị mụn
                'isHidden' => 0,
                'image' => 'cosmetics/teatree.jpg',
            ],
            [
                'name' => 'Phấn má hồng NARS Orgasm',
                'price' => 750000,
                'rating' => 4.8,
                'id_category' => $categoryIds[1], // Mỹ phẩm trang điểm
                'isHidden' => 0,
                'image' => 'cosmetics/nars.jpg',
            ],
            [
                'name' => 'Kem chống nắng Bioré',
                'price' => 185000,
                'rating' => 4.5,
                'id_category' => $categoryIds[2], // Mỹ phẩm chống nắng
                'isHidden' => 0,
                'image' => 'cosmetics/biore.jpg',
            ],
            [
                'name' => 'Mặt nạ Innisfree Green Tea',
                'price' => 30000,
                'rating' => 4.3,
                'id_category' => $categoryIds[0], // Mỹ phẩm dưỡng da
                'isHidden' => 0,
                'image' => 'cosmetics/innisfree.jpg',
            ],
            [
                'name' => 'Nước hoa hồng Thayers',
                'price' => 280000,
                'rating' => 4.6,
                'id_category' => $categoryIds[0], // Mỹ phẩm dưỡng da
                'isHidden' => 0,
                'image' => 'cosmetics/thayers.jpg',
            ],
            [
                'name' => 'Mascara Maybelline Lash Sensational',
                'price' => 160000,
                'rating' => 4.4,
                'id_category' => $categoryIds[1], // Mỹ phẩm trang điểm
                'isHidden' => 0,
                'image' => 'cosmetics/mascara.jpg',
            ],
        ];

        // Xóa dữ liệu cũ (nếu muốn)
        // DB::table('cosmetics')->truncate();

        // Thêm dữ liệu mới
        foreach ($cosmetics as $cosmetic) {
            Cosmetic::create($cosmetic);
        }
    }
}
