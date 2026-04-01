🚀 Workstation Booking System
Dự án Học tập Năm 2 - Team [Giang, Khánh, Mạnh, Huy]
Hệ thống quản lý và đặt chỗ ngồi làm việc (Workspace) dựa trên mô hình MVC, sử dụng Framework Laravel 11 và cơ sở dữ liệu MySQL. Dự án tập trung vào việc thực hành kết nối Full-stack và quy trình làm việc nhóm qua Git.

🛠 Tech Stack
Backend: PHP 8.3+ (Laravel 11)

Database: MySQL (MariaDB)

Frontend: Blade Template, Tailwind CSS / Bootstrap

Tooling: Laragon, Composer, VS Code

⚙️ Hướng dẫn Setup cho Teammate (Local Setup)

```
git clone https://github.com/soyuwu/Workstation_Project.git

```

Nếu bạn vừa clone dự án về, hãy thực hiện các bước sau để chạy web trên máy của mình:

1. Cài đặt môi trường
   Đảm bảo máy bạn đã cài đặt:
   Laragon Full (Đã bật Apache và MySQL).
   Composer (Kiểm tra bằng lệnh composer -v).

2. Cài đặt thư viện
   Mở Terminal tại thư mục dự án và chạy các lệnh cài đặt dependencies (bao gồm cả Laravel Core và PHPMailer):

```
composer install
```

3. Cấu hình file môi trường
   Tạo file .env từ file mẫu:

```
cp .env.example .env
```

Mở file .env vừa tạo và chỉnh sửa các thông số Database + Mail để khớp với máy cá nhân:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workstation_db
DB_USERNAME=root
DB_PASSWORD=

# Cấu hình Gmail SMTP (Dùng App Password)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=ssl
```

4. Khởi tạo Project & Database
   Chạy các lệnh sau theo thứ tự để thiết lập "chìa khóa" bảo mật và cấu trúc bảng:

# Tạo mã bảo mật ứng dụng

```
php artisan key:generate
```

# Tạo cấu trúc bảng (Lưu ý: Tạo database 'workstation_db' trước trong HeidiSQL/Navicat)

```
php artisan migrate
```

# (Tùy chọn) Chèn dữ liệu mẫu

```
php artisan db:seed
```

5. Chạy ứng dụng

```
php artisan serve
```

Truy cập: http://127.0.0.1:8000

#📁 Cấu trúc thư mục trọng tâm
app/Http/Controllers: Nơi xử lý logic nghiệp vụ (Auth, Booking, Admin).

app/Models: Định nghĩa thực thể (Users, Seats, Bookings, EmailVerifications).

database/migrations: Quản lý cấu trúc bảng (Sơ đồ DB).

resources/views: Giao diện Blade (Giao diện người dùng).

routes/web.php: Khai báo các đường dẫn URL của ứng dụng.

#🤝 Quy tắc làm việc nhóm (Git Flow)
Để tránh Conflict (Xung đột code), các thành viên cần tuân thủ:

Pull trước khi Code: Luôn chạy git pull vào đầu ngày làm việc.

Commit có tâm: Viết mô tả rõ ràng theo chuẩn:

feat: thêm bảng seats

fix: sửa lỗi kết nối DB

refactor: tối ưu hàm login

Bảo mật: Tuyệt đối không push file .env lên GitHub (Đã chặn bởi .gitignore).

#⚠️ Lưu ý quan trọng về Migration
Nếu bạn cần sửa đổi cấu trúc bảng (thêm cột, đổi kiểu dữ liệu):

Sửa trực tiếp trong file tương ứng tại database/migrations/.

Chạy lệnh sau để xóa sạch và tạo lại toàn bộ database theo cấu trúc mới:

```
php artisan migrate:fresh
```

(Cảnh báo: Lệnh này sẽ xóa sạch dữ liệu cũ trong bảng)
gi