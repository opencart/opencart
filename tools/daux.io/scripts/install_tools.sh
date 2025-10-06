#!/usr/bin/env bash

if [ ! -d build ]; then
  mkdir build;
fi

echo "-- Install php-cs-fixer"
curl -sSLo build/php-cs-fixer https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v3.13.0/php-cs-fixer.phar
chmod +x build/php-cs-fixer
