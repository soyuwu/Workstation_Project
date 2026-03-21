🚀 Workstation Booking System - Dự án Học tập Năm 2
Dự án này là hệ thống quản lý và đặt chỗ ngồi làm việc (Workspace) dựa trên mô hình MVC, sử dụng Framework Laravel và cơ sở dữ liệu MySQL. Mục tiêu chính là học cách kết nối giữa Frontend, Backend và Database, cũng như thực hành quy trình làm việc nhóm qua Git.

🛠 Tech Stack
Backend: PHP 8.3+ (Laravel 11)

Database: MySQL (MariaDB)

Frontend: Blade Template, Tailwind CSS / Bootstrap

Tooling: Laragon, Composer, VS Code

⚙️ Hướng dẫn Setup cho Teammate (Local Setup)
Nếu bạn vừa clone dự án này về, hãy thực hiện các bước sau để chạy web trên máy của mình:

1. Cài đặt môi trường
   Đảm bảo máy bạn đã có:

Laragon Full (Đã bật Apache và MySQL).

Composer (Đã cài và check bằng lệnh composer -v).

2. Cài đặt thư viện
   Mở Terminal tại thư mục dự án và chạy:

Bash

composer install 3. Cấu hình file môi trường
Copy file mẫu: cp .env.example .env (Hoặc đổi tên thủ công nếu dùng Windows Explorer).

Mở file .env và chỉnh sửa các thông số database:

Plaintext

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workstation_db
DB_USERNAME=root
DB_PASSWORD= 4. Khởi tạo Project Key và Database
Chạy các lệnh sau theo thứ tự:

Bash

# Tạo mã bảo mật cho ứng dụng

php artisan key:generate

# Tạo cấu trúc bảng vào MySQL (Nhớ tạo database workstation_db trước trong HeidiSQL)

php artisan migrate

# (Tùy chọn) Chèn dữ liệu mẫu nếu có

# php artisan db:seed

5. Chạy ứng dụng
   Bash

php artisan serve
Truy cập: http://127.0.0.1:8000

📁 Cấu trúc thư mục trọng tâm

# app/Http/Controllers: Nơi xử lý logic nghiệp vụ.

# app/Models: Nơi định nghĩa các thực thể (Seats, Bookings, Users).

# database/migrations: Nơi quản lý cấu trúc bảng.

# resources/views: Giao diện Blade (HTML).

# routes/web.php: Khai báo các đường dẫn URL.

🤝 Quy tắc làm việc nhóm (Git Flow)
Để tránh xung đột code (Conflict), mọi thành viên tuân thủ:

Luôn git pull trước khi bắt đầu code ngày mới.

Commit có tâm: Viết mô tả rõ ràng (Vd: feat: thêm bảng seats, fix: sửa lỗi kết nối DB).

Không push file .env lên GitHub (Đã được chặn bởi .gitignore).

📝 Ghi chú tính năng (Roadmap)
[x] Khởi tạo bộ khung Laravel.

[x] Kết nối thành công MySQL.

[ ] Thiết kế bảng Seats và tạo Migration.

[ ] Xây dựng tính năng quét mã QR để Check-in.

[ ] Dashboard quản lý trạng thái ghế (Trống/Đang ngồi).

Dự án được thực hiện bởi Team [Giang, Khánh, Mạnh, Huy] - Sinh viên Năm 2.

Lưu ý về migration: 1. Khi anh em đã tạo những bảng mà muốn sửa nhanh, anh em hãy sửa trực tiếp trong file : database/migrations/(tên file chưa bảng anh em muốn sửa) hãy sửa trực tiếp trong schema::create, sau đó anh em chạy php artisan migrate:fresh để nó xóa đi toàn bộ bảng và tạo mới lại toàn bộ theo ae mong muốn!
