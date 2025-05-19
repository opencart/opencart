FROM php:8.2.11-apache

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


RUN apt-get clean && apt-get update && apt-get install unzip

RUN apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libzip-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg\
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install zip && && docker-php-ext-enable zip\
  && docker-php-ext-enable mysqli

RUN apt-get install -y vim

RUN mkdir /storage && mkdir /opencart

RUN if [ -z "$DOWNLOAD_URL" ]; then \
  curl -Lo /tmp/opencart.zip $(sh -c 'curl -s https://api.github.com/repos/opencart/opencart/releases/latest | grep "browser_download_url" | cut -d : -f 2,3 | tr -d \"'); \
  else \
  curl -Lo /tmp/opencart.zip ${DOWNLOAD_URL}; \
  fi

RUN unzip /tmp/opencart.zip -d  /tmp/opencart;

RUN mv /tmp/opencart/$(if [ -n "$FOLDER" ]; then echo $FOLDER; else  unzip -l /tmp/opencart.zip | awk '{print $4}' | grep -E 'opencart-[a-z0-9.]+/upload/$'; fi)* ${DIR_OPENCART};

RUN rm -rf /tmp/opencart.zip && rm -rf /tmp/opencart && rm -rf ${DIR_OPENCART}install;

RUN mv ${DIR_OPENCART}system/storage/* /storage
COPY configs ${DIR_OPENCART}
COPY php.ini ${PHP_INI_DIR}

RUN a2enmod rewrite

RUN chown -R www-data:www-data ${DIR_STORAGE}
RUN chmod -R 555 ${DIR_OPENCART}
RUN chmod -R 666 ${DIR_STORAGE}
RUN chmod 555 ${DIR_STORAGE}
RUN chmod -R 555 ${DIR_STORAGE}vendor
RUN chmod 755 ${DIR_LOGS}
RUN chmod -R 644 ${DIR_LOGS}*

RUN chown -R www-data:www-data ${DIR_IMAGE}
RUN chmod -R 744 ${DIR_IMAGE}
RUN chmod -R 755 ${DIR_CACHE}

RUN chmod -R 666 ${DIR_DOWNLOAD}
RUN chmod -R 666 ${DIR_SESSION}
RUN chmod -R 666 ${DIR_UPLOAD}

CMD ["apache2-foreground"]
