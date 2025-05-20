FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    icu-dev \
    nodejs \
    npm \
    bash \
    mariadb-connector-c-dev \
    mariadb-client \
        && docker-php-ext-install pdo_mysql

# Install PHP extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        intl \
        opcache

# Configure OPcache
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.max_accelerated_files=50000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
# COPY . .

# # Copy composer files first
# COPY composer.json composer.lock ./

# # Create empty helpers file
# #RUN mkdir -p app/Helper && touch app/Helper/helpers.php

# # Install dependencies
# RUN composer install --no-interaction --optimize-autoloader --no-dev
# #RUN composer update


RUN chown -R www-data:www-data /var/www