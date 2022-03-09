#!/bin/bash

curl -sS https://getcomposer.org/installer | php -d suhosin.executor.include.whitelist=phar

php -d suhosin.executor.include.whitelist=phar ./composer.phar install

if [ "$1" == "hhvm" ]; then
  rake test:hhvm --trace
else
  rake test:php --trace
fi
