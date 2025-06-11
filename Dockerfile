# Stage 1: Build
FROM php:8.3-fpm AS build

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    curl \
    mariadb-client \
    libmariadb-dev \
    libfreetype6-dev \
    libmagickwand-dev \
    libmagickcore-dev \
    imagemagick \
    libheif-dev \
    build-essential \
    autoconf \
    pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Imagick
RUN pecl install imagick-3.6.0 && docker-php-ext-enable imagick || \
    (git clone https://github.com/Imagick/imagick.git /usr/src/imagick && \
    cd /usr/src/imagick && phpize && ./configure && make && make install && \
    docker-php-ext-enable imagick && rm -rf /usr/src/imagick)

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Cache Laravel configurations and routes
RUN php artisan config:cache && \
    php artisan route:cache

# Configure Opcache
RUN { \
    echo "opcache.memory_consumption=128"; \
    echo "opcache.interned_strings_buffer=8"; \
    echo "opcache.max_accelerated_files=10000"; \
    echo "opcache.revalidate_freq=0"; \
    echo "opcache.validate_timestamps=0"; \
    echo "opcache.enable_cli=1"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP upload and post size limits
RUN { \
    echo "upload_max_filesize=100M"; \
    echo "post_max_size=110M"; \
    echo "memory_limit=512M"; \
    echo "max_execution_time=120"; \
    echo "max_input_time=120"; \
} > /usr/local/etc/php/conf.d/uploads.ini

# Stage 2: Production
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    curl \
    mariadb-client \
    libmariadb-dev \
    libfreetype6-dev \
    libmagickwand-dev \
    libmagickcore-dev \
    imagemagick \
    libheif-dev \
    build-essential \
    autoconf \
    pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Imagick
RUN pecl install imagick-3.6.0 && docker-php-ext-enable imagick || \
    (git clone https://github.com/Imagick/imagick.git /usr/src/imagick && \
    cd /usr/src/imagick && phpize && ./configure && make && make install && \
    docker-php-ext-enable imagick && rm -rf /usr/src/imagick)

# Copy extensions and application
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/bin/composer /usr/local/bin/composer
COPY --from=build /var/www/html /var/www/html
COPY --from=build /usr/local/etc/php /usr/local/etc/php

# Copy entrypoint script
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set working directory and permissions
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Configure PHP-FPM pool
RUN { \
    echo "[www]"; \
    echo "user = www-data"; \
    echo "group = www-data"; \
    echo "listen = 9000"; \
    echo "listen.backlog = 4096"; \
    echo "pm = dynamic"; \
    echo "pm.max_children = 180"; \
    echo "pm.start_servers = 30"; \
    echo "pm.min_spare_servers = 20"; \
    echo "pm.max_spare_servers = 40"; \
    echo "pm.max_requests = 500"; \
    echo "request_terminate_timeout = 300s"; \
    echo "rlimit_files = 65535"; \
} > /usr/local/etc/php-fpm.d/www.conf

# Expose port 9000
EXPOSE 9000

# Use entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
