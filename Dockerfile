FROM php:8.2-apache

ARG DOWNLOAD_URL
ARG FOLDER

ENV DIR_OPENCART='/var/www/html/'
ENV DIR_STORAGE='/storage/'
ENV DIR_CACHE=${DIR_STORAGE}'cache/'
ENV DIR_DOWNLOAD=${DIR_STORAGE}'download/'
ENV DIR_LOGS=${DIR_STORAGE}'logs/'
ENV DIR_SESSION=${DIR_STORAGE}'session/'
ENV DIR_UPLOAD=${DIR_STORAGE}'upload/'
ENV DIR_IMAGE=${DIR_OPENCART}'image/'

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        unzip \
        ca-certificates \
        curl \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        libonig-dev \
        libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd zip mysqli mbstring \
    && docker-php-ext-enable gd zip mysqli mbstring \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /storage /opencart

RUN if [ -z "$DOWNLOAD_URL" ]; then \
        curl -fsSLo /tmp/opencart.zip $(curl -fsSL https://api.github.com/repos/opencart/opencart/releases/latest | grep "browser_download_url" | head -1 | cut -d '"' -f 4); \
    else \
        curl -fsSLo /tmp/opencart.zip "${DOWNLOAD_URL}"; \
    fi

RUN unzip -q /tmp/opencart.zip -d /tmp/opencart \
    && mv /tmp/opencart/$(if [ -n "$FOLDER" ]; then echo "$FOLDER"; else unzip -l /tmp/opencart.zip | awk '{print $4}' | grep -E 'opencart-[a-z0-9.]+/upload/$'; fi)* ${DIR_OPENCART} \
    && rm -rf /tmp/opencart.zip /tmp/opencart \
    && rm -rf ${DIR_OPENCART}install

RUN mv ${DIR_OPENCART}system/storage/* /storage
COPY configs ${DIR_OPENCART}
COPY php.ini ${PHP_INI_DIR}/

RUN a2enmod rewrite

RUN chown -R www-data:www-data ${DIR_STORAGE} ${DIR_IMAGE} \
    && chmod -R 555 ${DIR_OPENCART} \
    && chmod -R 666 ${DIR_STORAGE} \
    && chmod 555 ${DIR_STORAGE} \
    && chmod -R 555 ${DIR_STORAGE}vendor \
    && chmod 755 ${DIR_LOGS} \
    && chmod -R 644 ${DIR_LOGS}* \
    && chmod -R 744 ${DIR_IMAGE} \
    && chmod -R 755 ${DIR_CACHE} \
    && chmod -R 666 ${DIR_DOWNLOAD} ${DIR_SESSION} ${DIR_UPLOAD}

CMD ["apache2-foreground"]