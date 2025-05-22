FROM php:8.2-fpm

# Install ekstensi dan dependencies yang diperlukan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    supervisor \
    && docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql zip

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin file project
COPY . .

# Permissions untuk Laravel
RUN mkdir -p storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Salin file Supervisor konfigurasi
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 8000

# Jalankan Supervisor
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
