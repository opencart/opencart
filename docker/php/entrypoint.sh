#!/bin/bash
set -e

if [ ! -f /var/www/html/install.lock ]; then
  cp config-dist.php config.php
  cp admin/config-dist.php admin/config.php
  php /var/www/html/install/cli_install.php install \
    --username admin \
    --password admin \
    --email email@example.com \
    --http_server http://localhost/ \
    --db_driver mysqli \
    --db_hostname mysql \
    --db_username root \
    --db_password opencart \
    --db_database opencart \
    --db_port 3306 \
    --db_prefix oc_
  touch /var/www/html/install.lock
fi

exec apache2-foreground
