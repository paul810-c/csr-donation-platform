FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    zip unzip curl git libonig-dev libxml2-dev libzip-dev libssl-dev librabbitmq-dev libssh-dev \
    libpq-dev libicu-dev libmcrypt-dev libjpeg-dev libpng-dev libwebp-dev libfreetype6-dev \
    libxslt-dev libyaml-dev libzip-dev netcat-openbsd \
    && docker-php-ext-install sockets pdo_mysql mbstring zip exif pcntl \
    && pecl install amqp && docker-php-ext-enable amqp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
