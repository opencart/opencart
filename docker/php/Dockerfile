# This argument defines the PHP version for the image.
# It defaults to 8.4 if no 'PHP_VERSION' build-arg is passed from docker-compose.
ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-apache

# Copy the advanced PHP extension installer.
RUN apt-get update && apt-get install -y --no-install-recommends unzip

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install only the extensions that are missing from the base image but required by OpenCart.
# opcache is also included for performance improvements.
RUN install-php-extensions \
    @composer \
    gd \
    mysqli \
    zip \
    opcache

# Set entrypoint script for container startup.
COPY --chown=www-data:www-data entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Enable mod_rewrite for SEO-friendly URLs, as it's not enabled by default.
RUN a2enmod rewrite
