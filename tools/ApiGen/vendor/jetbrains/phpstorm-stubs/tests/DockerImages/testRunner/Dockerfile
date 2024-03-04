FROM php:8.1-apache

RUN echo 'memory_limit = 1024M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get -y install git zip unzip

WORKDIR /opt/project/phpstorm-stubs
