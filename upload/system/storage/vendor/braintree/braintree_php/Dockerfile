FROM debian:jessie

RUN apt-get update
RUN apt-get -y install gnupg curl

# For installing hhvm
RUN apt-get install -y apt-transport-https software-properties-common
RUN apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xB4112585D386EB94
RUN echo deb https://dl.hhvm.com/debian jessie main > /etc/apt/sources.list.d/hhvm.list
RUN apt-get update

RUN apt-get -y install rake php5 php5-cli php5-curl php-pear hhvm phpunit

WORKDIR /braintree-php
