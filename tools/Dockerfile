FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y \
        wait-for-it \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libcurl3-dev \
        libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd zip mysqli curl \
    && docker-php-ext-enable gd zip mysqli curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
