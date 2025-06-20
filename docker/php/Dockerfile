# This argument defines the PHP version for the image.
# It defaults to 8.4 if no 'PHP_VERSION' build-arg is passed from docker-compose.
ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-fpm-alpine

# Install PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    @composer \
    gd \
    mysqli \
    pdo_mysql \
    pdo_pgsql \
    zip \
    opcache \
    apcu \
    redis \
    memcached

# Copy OpenCart's original php.ini
COPY upload/php.ini /usr/local/etc/php/conf.d/opencart.ini

# Copy OpenCart entrypoint
COPY docker/php/docker-opencart-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-opencart-entrypoint.sh

# Set working directory
WORKDIR /var/www/html

# Use OpenCart entrypoint
ENTRYPOINT ["docker-opencart-entrypoint.sh"]
CMD ["php-fpm"]
