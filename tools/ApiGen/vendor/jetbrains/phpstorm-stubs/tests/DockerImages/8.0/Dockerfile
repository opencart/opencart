FROM php:8.0-alpine

RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
    bash gcc g++ make autoconf pkgconfig git \
    libmcrypt-dev imap-dev php8-imap enchant2 php8-enchant bzip2-dev gettext-dev libxml2-dev php8-dev php8-gd icu-dev \
    php8-zip php8-tidy php8-intl libffi-dev openssl-dev php8-pear rabbitmq-c rabbitmq-c-dev librrd \
    libzip-dev rrdtool-dev gmp-dev yaml yaml-dev fann fann-dev openldap-dev librdkafka librdkafka-dev libcurl curl-dev \
    libpng-dev gpgme gpgme-dev libpq-dev aspell-dev

RUN docker-php-ext-install imap gmp sockets intl gd ldap bz2 mysqli bcmath calendar dba exif gettext opcache pcntl \
    pdo_mysql shmop sysvmsg sysvsem sysvshm xml soap pgsql pspell

WORKDIR /opt/project/phpstorm-stubs
