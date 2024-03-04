FROM php:5.6-alpine
RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
    bash gcc g++ make autoconf pkgconfig git \
    libmcrypt-dev imap-dev php7-imap php7-enchant bzip2-dev gettext-dev libxml2-dev php7-dev php7-gd icu-dev \
    php7-zip php7-tidy php7-intl libffi-dev openssl-dev php7-pear rabbitmq-c librrd libzip-dev rrdtool-dev \
    gmp-dev yaml yaml-dev fann fann-dev

RUN docker-php-ext-install imap gmp sockets intl bz2 mysqli bcmath calendar dba exif gettext opcache pcntl \
    pdo_mysql shmop sysvmsg sysvsem sysvshm xml soap

WORKDIR /opt/project/phpstorm-stubs