FROM php:8.3-cli

# Install system dependencies & Node.js for Vite
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-interaction --no-dev

# Install NPM dependencies & Compile Frontend Assets
RUN npm install && npm run build

# Make sure permissions are correct for Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

ENV PORT=8000
EXPOSE $PORT

# Jalankan migrate, seed, dan nyalakan web-server sekaligus saat container start
CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
