<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'rating' => 5,
            'content' => 'Mình là freelancer, trước giờ hay làm việc ở quán cà phê nhưng luôn bị phân tán. Từ khi đến WorkStation, mình tập trung hơn hẳn. Wifi nhanh, không gian yên tĩnh và cộng đồng rất thân thiện!',
            'author_name' => 'Trần Minh Tuấn',
            'author_role' => 'Freelance Designer',
            'is_approved' => true,
        ]);

        Review::create([
            'rating' => 5,
            'content' => 'Team mình thuê văn phòng riêng ở WorkStation được 6 tháng rồi. Giá hợp lý hơn nhiều so với thuê văn phòng truyền thống, mà mọi thứ đều đã bao gồm. Rất tiện lợi cho startup!',
            'author_name' => 'Lê Hoàng Nam',
            'author_role' => 'CEO, TechVi Startup',
            'is_approved' => true,
        ]);

        Review::create([
            'rating' => 4.5,
            'content' => 'Phòng hội thảo của WorkStation rất chuyên nghiệp. Mình đã tổ chức 3 workshop ở đây, khách tham dự đều ấn tượng với không gian và trang thiết bị. Sẽ quay lại nhiều lần nữa!',
            'author_name' => 'Phạm Ngọc Hà',
            'author_role' => 'Marketing Manager',
            'is_approved' => true,
        ]);

        Review::create([
            'rating' => 5,
            'content' => 'Là sinh viên IT, mình cần chỗ yên tĩnh để code. WorkStation có chỗ ngồi giá sinh viên rất hợp lý, wifi siêu nhanh và ổ cắm ở khắp nơi. Mình giới thiệu cho cả nhóm bạn rồi!',
            'author_name' => 'Nguyễn Thị Mai',
            'author_role' => 'Sinh viên UIT',
            'is_approved' => true,
        ]);

        Review::create([
            'rating' => 5,
            'content' => 'Đặt chỗ online trên website rất thuận tiện, chỉ vài click là xong. Đội ngũ nhân viên thân thiện, hỗ trợ nhanh. WorkStation đúng nghĩa là ngôi nhà thứ hai cho dân văn phòng!',
            'author_name' => 'Đỗ Văn Khoa',
            'author_role' => 'Product Manager, FPT',
            'is_approved' => true,
        ]);
    }
}
