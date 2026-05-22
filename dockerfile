# Sử dụng PHP 8.2 FPM làm base
FROM php:8.2-fpm

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nodejs npm

# Cài đặt PHP extensions cho MySQL và xử lý chuỗi
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Lấy Composer mới nhất
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . /var/www

# Cài đặt dependencies dựa trên file composer.json bạn cung cấp
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Cài đặt và build assets với Vite
RUN npm install && npm run build

# Phân quyền cho Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]