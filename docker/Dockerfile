FROM php:apache

RUN docker-php-ext-install mysqli

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install git (needed to fetch Zxcvbn)
RUN apt-get -y update
RUN apt-get -y install git && apt-get -y install unzip
# Install zxcvbn
WORKDIR /var/www/html/
RUN composer require bjeavons/zxcvbn-php
RUN composer update
