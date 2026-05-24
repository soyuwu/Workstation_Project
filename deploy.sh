#!/bin/bash

# Bật chế độ dừng script ngay lập tức nếu có bất kỳ lệnh nào bị lỗi
set -e

echo "🚀 [1/6] Đang dọn dẹp hệ thống cũ và xóa Volume data..."
docker compose down -v

echo "📥 [2/6] Đang kéo code mới nhất từ GitHub..."
git pull origin main

echo "📦 [3/6] Đang build lại các Image Docker và khởi động Container..."
docker compose up -d --build

echo "⏳ Đợi 5 giây cho Database khởi động hoàn toàn..."
sleep 5

echo "🗄️ [4/6] Khởi tạo lại Database sạch và gieo dữ liệu Admin..."
docker compose exec -T app php artisan migrate:fresh --seed

echo "🎨 [5/6] Cài đặt thư viện JS và build asset với Vite..."
docker compose exec -T app npm install
docker compose exec -T app npm run build

echo "⚡ [6/6] Tối ưu hóa và làm sạch bộ nhớ Cache Laravel..."
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

echo "🧹 [Bonus] Đang dọn dẹp các tài nguyên Docker rác trên VPS..."
docker system prune -a --volumes -f

echo "🎉 DEPLOY THÀNH CÔNG! Server sạch 100% đã sẵn sàng hoạt động tại tên miền của bạn."