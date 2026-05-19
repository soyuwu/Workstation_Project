<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;

Route::get('/', function () {
    return view('LandingPage.welcome');
});

// đường dẫn qua trang signIn/signUp dùng chung 1 form
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register', [AuthController::class, 'showAuthForm'])->name('register');

// Duong dan qua trang LogIn
Route::post('/logIn', [AuthController::class, 'logIn']);
Route::get('/logIn', [AuthController::class, 'showAuthForm'])->name('logIn');

// LogOut
Route::get('/logOut', [AuthController::class, 'logOut'])->name('logOut');

// Trang Dịch vụ
Route::get('/khong-gian', function () {
    return view('services.khong-gian');
})->name('khongGian');

Route::get('/dich-vu', function () {
    return view('services.dich-vu');
})->name('dichVu');

// Chi tiết dịch vụ từng loại phòng
Route::get('/dich-vu/{slug}', function ($slug) {
    $services = [
        'cho-ngoi-linh-hoat' => [
            'name' => 'Chỗ ngồi linh hoạt',
            'icon' => 'event_seat',
            'badge' => 'Hot Desk',
            'tagline' => 'Tự do chọn chỗ, linh hoạt thời gian – đến bất kỳ lúc nào bạn muốn.',
            'headline' => 'Làm việc mọi lúc, không ràng buộc',
            'description' => 'Chỗ ngồi linh hoạt (Hot Desk) là giải pháp hoàn hảo cho freelancer, sinh viên và những người cần một không gian làm việc chuyên nghiệp mà không bị ràng buộc bởi hợp đồng dài hạn. Bạn chỉ cần đến, chọn chỗ trống bất kỳ và bắt đầu làm việc.',
            'description_2' => 'Mỗi chỗ ngồi đều được trang bị ổ cắm điện, đèn bàn và ghế công thái học. Bạn được sử dụng wifi tốc độ cao, trà – cà phê miễn phí, và có thể sử dụng phòng họp với giá ưu đãi dành riêng cho thành viên.',
            'price' => '50.000đ',
            'price_unit' => 'giờ',
            'capacity' => '1 người',
            'hero_image' => 'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=2070&auto=format&fit=crop',
            'detail_image' => asset('Images/ghedon.jpg'),
            'audience_image' => asset('Images/Linhhoat.jpg'),
            'features' => [
                ['icon' => 'wifi', 'title' => 'Wifi tốc độ cao', 'desc' => 'Fiber 1Gbps ổn định, backup 4G khi mất mạng.'],
                ['icon' => 'local_cafe', 'title' => 'Trà & Cà phê miễn phí', 'desc' => 'Thưởng thức đồ uống không giới hạn suốt ngày.'],
                ['icon' => 'power', 'title' => 'Ổ cắm mọi chỗ', 'desc' => 'Mỗi bàn đều có ổ cắm điện và cổng USB.'],
                ['icon' => 'ac_unit', 'title' => 'Điều hòa mát mẻ', 'desc' => 'Nhiệt độ luôn duy trì 24-26°C thoải mái.'],
                ['icon' => 'lock', 'title' => 'Tủ khóa cá nhân', 'desc' => 'Thuê tủ khóa để gửi đồ qua đêm (phụ thu).'],
                ['icon' => 'print', 'title' => 'Máy in & scan', 'desc' => 'In ấn tài liệu ngay tại không gian làm việc.'],
            ],
            'target_audience' => [
                ['title' => 'Freelancer & Lập trình viên', 'desc' => 'Cần không gian yên tĩnh, wifi nhanh để làm việc hiệu quả.'],
                ['title' => 'Sinh viên đại học', 'desc' => 'Ôn thi, làm đồ án nhóm với chi phí hợp lý.'],
                ['title' => 'Người làm việc từ xa', 'desc' => 'Remote worker cần thay đổi không khí, thoát khỏi nhà.'],
                ['title' => 'Doanh nhân khởi nghiệp', 'desc' => 'Tiết kiệm chi phí văn phòng khi mới bắt đầu.'],
            ],
        ],

        'cho-ngoi-co-dinh' => [
            'name' => 'Chỗ ngồi cố định',
            'icon' => 'chair',
            'badge' => 'Dedicated Desk',
            'tagline' => 'Bàn làm việc riêng, vị trí cố định – không gian quen thuộc mỗi ngày.',
            'headline' => 'Bàn riêng, không gian của bạn',
            'description' => 'Chỗ ngồi cố định (Dedicated Desk) dành cho những ai muốn có một vị trí cố định tại coworking space. Bàn làm việc được dành riêng cho bạn 24/7, kèm tủ khóa cá nhân để lưu trữ đồ dùng qua đêm.',
            'description_2' => 'Đây là lựa chọn lý tưởng nếu bạn cần sự ổn định nhưng vẫn muốn tận hưởng cộng đồng năng động của coworking. Bạn có thể cá nhân hóa góc làm việc, để màn hình phụ, sách vở mà không cần dọn dẹp mỗi ngày.',
            'price' => '2.500.000đ',
            'price_unit' => 'tháng',
            'capacity' => '1 người',
            'hero_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop',
            'detail_image' => asset('Images/Linhhoat.jpg'),
            'audience_image' => asset('Images/ghedon.jpg'),
            'features' => [
                ['icon' => 'desk', 'title' => 'Bàn riêng 24/7', 'desc' => 'Vị trí cố định, không ai chiếm chỗ của bạn.'],
                ['icon' => 'lock', 'title' => 'Tủ khóa cá nhân', 'desc' => 'Tủ khóa riêng để gửi đồ dùng, laptop qua đêm.'],
                ['icon' => 'wifi', 'title' => 'Wifi tốc độ cao', 'desc' => 'Fiber 1Gbps ổn định, phù hợp cho dev và designer.'],
                ['icon' => 'local_cafe', 'title' => 'Đồ uống miễn phí', 'desc' => 'Trà, cà phê, nước lọc không giới hạn.'],
                ['icon' => 'meeting_room', 'title' => 'Giờ phòng họp miễn phí', 'desc' => '4 giờ phòng họp miễn phí mỗi tháng.'],
                ['icon' => 'mail', 'title' => 'Nhận thư & bưu phẩm', 'desc' => 'Sử dụng địa chỉ coworking để nhận thư.'],
            ],
            'target_audience' => [
                ['title' => 'Freelancer toàn thời gian', 'desc' => 'Cần không gian ổn định để làm việc mỗi ngày.'],
                ['title' => 'Nhân viên remote', 'desc' => 'Làm việc từ xa cho công ty nước ngoài, cần bàn cố định.'],
                ['title' => 'Designer & Content Creator', 'desc' => 'Cần để màn hình phụ, tablet và dụng cụ làm việc.'],
                ['title' => 'Chủ doanh nghiệp nhỏ', 'desc' => 'Chưa cần văn phòng riêng nhưng muốn sự chuyên nghiệp.'],
            ],
        ],

        'phong-lam-viec-rieng' => [
            'name' => 'Phòng làm việc riêng',
            'icon' => 'corporate_fare',
            'badge' => 'Private Office',
            'tagline' => 'Văn phòng khép kín, riêng tư tuyệt đối – chuyên nghiệp cho team của bạn.',
            'headline' => 'Văn phòng riêng cho team',
            'description' => 'Phòng làm việc riêng (Private Office) là không gian văn phòng khép kín, có khóa, dành cho team từ 2 đến 10 người. Được thiết kế cách âm, đảm bảo sự riêng tư tuyệt đối cho những cuộc họp quan trọng và công việc cần sự tập trung cao.',
            'description_2' => 'Mỗi phòng được trang bị đầy đủ bàn ghế, điều hòa riêng, tủ hồ sơ và bảng trắng. Bạn có thể trang trí và sắp xếp phòng theo ý thích, biến nó thành văn phòng thực sự của team mình.',
            'price' => '5.000.000đ',
            'price_unit' => 'tháng',
            'capacity' => '2-10 người',
            'hero_image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?q=80&w=2070&auto=format&fit=crop',
            'detail_image' => asset('Images/Vanphong.webp'),
            'audience_image' => asset('Images/Banhocnhom.jpg'),
            'features' => [
                ['icon' => 'lock', 'title' => 'Khóa cửa riêng', 'desc' => 'Phòng có khóa, an toàn cho tài liệu và thiết bị.'],
                ['icon' => 'volume_off', 'title' => 'Cách âm chuyên nghiệp', 'desc' => 'Tường cách âm, không bị ảnh hưởng bởi bên ngoài.'],
                ['icon' => 'ac_unit', 'title' => 'Điều hòa riêng', 'desc' => 'Kiểm soát nhiệt độ phòng theo ý thích.'],
                ['icon' => 'desktop_windows', 'title' => 'Bàn ghế đầy đủ', 'desc' => 'Bàn làm việc, ghế công thái học, tủ hồ sơ.'],
                ['icon' => 'meeting_room', 'title' => '8 giờ phòng họp/tháng', 'desc' => 'Sử dụng phòng họp miễn phí 8 giờ mỗi tháng.'],
                ['icon' => 'security', 'title' => 'An ninh 24/7', 'desc' => 'Camera giám sát, bảo vệ trực và hệ thống kiểm soát ra vào.'],
            ],
            'target_audience' => [
                ['title' => 'Startup 2-10 người', 'desc' => 'Cần văn phòng chuyên nghiệp mà không phải lo chi phí lớn.'],
                ['title' => 'Doanh nghiệp vừa & nhỏ', 'desc' => 'Chi nhánh nhỏ hoặc team dự án cần không gian riêng.'],
                ['title' => 'Công ty nước ngoài', 'desc' => 'Mở văn phòng đại diện tại Việt Nam nhanh chóng.'],
                ['title' => 'Team phát triển phần mềm', 'desc' => 'Cần sự riêng tư để tập trung code và họp nội bộ.'],
            ],
        ],

        'phong-hop-tieu-chuan' => [
            'name' => 'Phòng họp tiêu chuẩn',
            'icon' => 'meeting_room',
            'badge' => 'Meeting Room',
            'tagline' => 'Phòng họp chuyên nghiệp, trang bị đầy đủ – sẵn sàng cho mọi cuộc họp.',
            'headline' => 'Phòng họp hiện đại, chuyên nghiệp',
            'description' => 'Phòng họp tiêu chuẩn tại WorkStation được trang bị TV màn hình lớn, camera hội nghị truyền hình, bảng trắng và hệ thống âm thanh chất lượng cao. Sức chứa từ 4 đến 12 người, phù hợp cho mọi loại cuộc họp.',
            'description_2' => 'Bạn có thể đặt phòng theo giờ hoặc cả ngày. Phòng được chuẩn bị sẵn nước uống, giấy note và bút. Đội ngũ nhân viên sẽ hỗ trợ setup kỹ thuật nếu bạn cần trình chiếu hoặc kết nối video call.',
            'price' => '200.000đ',
            'price_unit' => 'giờ',
            'capacity' => '4-12 người',
            'hero_image' => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?q=80&w=2070&auto=format&fit=crop',
            'detail_image' => asset('Images/Phonghoithao.jpg'),
            'audience_image' => asset('Images/Banhocnhom.jpg'),
            'features' => [
                ['icon' => 'tv', 'title' => 'TV màn hình lớn', 'desc' => 'Màn hình 55-65 inch, hỗ trợ HDMI và wireless cast.'],
                ['icon' => 'videocam', 'title' => 'Hội nghị truyền hình', 'desc' => 'Camera và mic chuyên dụng cho video call nhóm.'],
                ['icon' => 'edit_note', 'title' => 'Bảng trắng', 'desc' => 'Bảng trắng lớn cho brainstorm và ghi chú.'],
                ['icon' => 'volume_up', 'title' => 'Âm thanh chất lượng', 'desc' => 'Loa và micro conference chuyên nghiệp.'],
                ['icon' => 'local_cafe', 'title' => 'Nước uống phục vụ', 'desc' => 'Trà, cà phê và nước đóng chai sẵn trong phòng.'],
                ['icon' => 'support_agent', 'title' => 'Hỗ trợ kỹ thuật', 'desc' => 'Nhân viên IT hỗ trợ setup thiết bị trình chiếu.'],
            ],
            'target_audience' => [
                ['title' => 'Doanh nghiệp cần họp khách hàng', 'desc' => 'Không gian chuyên nghiệp để gặp gỡ và thuyết trình.'],
                ['title' => 'Team dự án', 'desc' => 'Sprint planning, retrospective, brainstorm ý tưởng.'],
                ['title' => 'Phỏng vấn tuyển dụng', 'desc' => 'Phòng riêng tư, yên tĩnh cho buổi phỏng vấn.'],
                ['title' => 'Training & đào tạo nhóm nhỏ', 'desc' => 'Đào tạo nội bộ, workshop kỹ năng cho team.'],
            ],
        ],

        'khong-gian-su-kien' => [
            'name' => 'Không gian sự kiện',
            'icon' => 'celebration',
            'badge' => 'Event Space',
            'tagline' => 'Sân khấu cho ý tưởng lớn – tổ chức sự kiện quy mô từ 20 đến 100 người.',
            'headline' => 'Không gian lý tưởng cho sự kiện',
            'description' => 'Không gian sự kiện tại WorkStation được thiết kế linh hoạt, có thể biến đổi bố cục để phù hợp với nhiều loại hình: workshop, hội thảo, ra mắt sản phẩm, meetup cộng đồng hay tiệc networking. Sức chứa lên đến 100 người.',
            'description_2' => 'Trang bị projector, hệ thống âm thanh chuyên nghiệp, micro không dây, sân khấu nhỏ và khu vực tiếp khách. Đội ngũ sự kiện của WorkStation sẽ hỗ trợ bạn từ khâu setup đến vận hành trong suốt buổi event.',
            'price' => '500.000đ',
            'price_unit' => 'giờ',
            'capacity' => '20-100 người',
            'hero_image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=2012&auto=format&fit=crop',
            'detail_image' => asset('Images/Phonghoithao.jpg'),
            'audience_image' => asset('Images/khonggian.jpg'),
            'features' => [
                ['icon' => 'present_to_all', 'title' => 'Projector & màn chiếu', 'desc' => 'Projector full HD, màn chiếu lớn cho trình chiếu chuyên nghiệp.'],
                ['icon' => 'mic', 'title' => 'Micro không dây', 'desc' => '2-4 micro không dây, hệ thống loa chuyên nghiệp.'],
                ['icon' => 'podium', 'title' => 'Sân khấu & bục phát biểu', 'desc' => 'Sân khấu nhỏ, bục phát biểu và backdrop tùy chỉnh.'],
                ['icon' => 'chair_alt', 'title' => 'Bàn ghế linh hoạt', 'desc' => 'Sắp xếp bàn ghế theater, classroom hoặc banquet.'],
                ['icon' => 'restaurant', 'title' => 'Khu tiếp khách & tea break', 'desc' => 'Khu vực phục vụ tea break, networking ngoài sảnh.'],
                ['icon' => 'person', 'title' => 'Nhân sự hỗ trợ', 'desc' => 'Đội ngũ sự kiện hỗ trợ setup và vận hành.'],
            ],
            'target_audience' => [
                ['title' => 'Công ty tổ chức workshop', 'desc' => 'Training, workshop kỹ năng cho nhân viên hoặc cộng đồng.'],
                ['title' => 'Startup ra mắt sản phẩm', 'desc' => 'Launch event, demo day, pitch competition.'],
                ['title' => 'Cộng đồng công nghệ', 'desc' => 'Meetup, tech talk, hackathon và networking event.'],
                ['title' => 'Doanh nghiệp tổ chức hội thảo', 'desc' => 'Seminar, conference, họp cổ đông quy mô vừa.'],
            ],
        ],
    ];

    if (!isset($services[$slug])) {
        abort(404);
    }

    return view('services.detail', ['service' => $services[$slug]]);
})->name('dichvu.detail');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgetPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.update');
// Booking System
Route::prefix('booking')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/monthly/{type}', [BookingController::class, 'monthly'])->name('booking.monthly');
    Route::get('/hourly/{type}', [BookingController::class, 'hourly'])->name('booking.hourly');
    Route::get('/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/process', [BookingController::class, 'processBooking'])->name('booking.process');
    // Luồng đặt tháng
    Route::get('/monthly-checkout', [BookingController::class, 'monthlyCheckout'])->name('booking.monthly.checkout');
    Route::post('/monthly-process', [BookingController::class, 'processMonthlyBooking'])->name('booking.monthly.process');
});

// Payment System
use App\Http\Controllers\PaymentController;
Route::prefix('payment')->group(function () {
    // Thanh toán MoMo
    Route::get('/momo-return', [PaymentController::class, 'momoReturn'])->name('payment.momo_return');
    Route::post('/momo-ipn', [PaymentController::class, 'momoIPN'])->name('payment.momo_ipn');

    // Thanh toán VietQR
    Route::get('/vietqr/{booking_code}', [PaymentController::class, 'vietqr'])->name('payment.vietqr');
    Route::post('/vietqr/confirm/{booking_code}', [PaymentController::class, 'confirmVietqr'])->name('payment.vietqr.confirm');

    // Webhook tự động từ SePay
    Route::post('/webhook', [PaymentController::class, 'sepayWebhook'])->name('payment.sepay_webhook');
});


use App\Http\Controllers\AdminController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/tongquan', [AdminController::class, 'tongquan'])->name('tongquan');
    Route::get('/booking', [AdminController::class, 'booking'])->name('booking');
    Route::post('/booking/{id}/approve', [AdminController::class, 'approveBooking'])->name('booking.approve');
    Route::post('/booking/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('booking.cancel');
    Route::get('/facility', function () {
        return view('admin.map');
    })->name('facility');
    Route::get('/fnb', function () {
        return view('admin.fnb');
    })->name('fnb');
    
    Route::get('/voucher', [AdminController::class, 'voucher'])->name('voucher');
    Route::post('/voucher', [AdminController::class, 'storeVoucher'])->name('voucher.store');
    Route::put('/voucher/{id}', [AdminController::class, 'storeVoucher']);
    Route::delete('/voucher/{id}', [AdminController::class, 'destroyVoucher']);
    
    Route::get('/taikhoan', [AdminController::class, 'taikhoan'])->name('taikhoan');
    Route::post('/taikhoan', [AdminController::class, 'storeTaikhoan'])->name('taikhoan.store');
    Route::put('/taikhoan/{id}', [AdminController::class, 'storeTaikhoan']);
    Route::delete('/taikhoan/{id}', [AdminController::class, 'destroyTaikhoan']);

    Route::get('/workspace', [AdminController::class, 'workspace'])->name('workspace');
    Route::post('/workspace', [AdminController::class, 'storeWorkspace'])->name('workspace.store');
    Route::put('/workspace/{id}', [AdminController::class, 'storeWorkspace']);
    Route::delete('/workspace/{id}', [AdminController::class, 'destroyWorkspace']);


});


// Route::get('/test-mail', function () {
//     $auth = new \App\Http\Controllers\Auth\AuthController();
//     $result = $auth->sendActivationEmail('24520422@gm.uit.edu.vn', 'Test User', 'http://127.0.0.1:8000/test');
//     return $result ? "Mail đã gửi thành công!" : "Gửi mail thất bại, hãy kiểm tra log.";
// }); TEST TINH NANG GUI MAIL TU DONG 

// Route::get('/signin', function () {
//     return view('auth.SignIn');
// })->name('SignIn');

// Route::get('/thongbao', function () {
//     $so1 = app('thongBaoDauTien'); 
//     return $so1->index();
// });

// Route::get('/cache-put', function () {
//     $cache = app()->make('cache');
//     $cache->put('name','Giang',20);
//     return 'Da luu gia tri thanh cong';         //put-get cache truyền thống!!!   
// });

// Route::get('/cache-get', function () {
//     $cache = app()->make('cache');
//     $value = $cache->get('name');
//     return $value;
// });

// //put-get dùng facades
// Route::get('/cache-put', function () {
//     Cache::put('name','Pham Truong Giang',20);
//     return 'da luu vao cache thanh cong';    
// })->name('putCache');

// Route::get('/cache-get', function () {
//     $value = Cache::get('name');
//     return $value;
// })->name('getCache');

// Route::get('/user', function () {
//     session(['name' => 'Truong Giang'], ['age' => 20]);
//     echo (Session->name);
//     return session('age');
// });

// Route::get('/user', function(Request $request) {

// });