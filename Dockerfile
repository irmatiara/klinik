FROM php:8.2-fpm-alpine

# Install System Dependencies & PHP Extensions
RUN apk add --no-cache \
    nginx \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Project Files
COPY . .

# Install PHP & Node Dependencies & Build Assets
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Storage & Cache Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy Nginx Config
COPY .render/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

# Start Command
CMD sh -c "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && php-fpm -D && nginx -g 'daemon off;'"
