FROM php:8-apache

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# pdo pdo_mysql
# but pdo is broken right now it seems?
RUN install-php-extensions gd zip mysqli

COPY --chown=www-data:www-data upload/ ./

COPY docker-entrypoint.sh /docker-entrypoint.sh
#RUN mv config-dist.php config.php; mv admin/config-dist.php admin/config.php
ENTRYPOINT /docker-entrypoint.sh
