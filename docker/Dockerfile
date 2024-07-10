FROM php:apache

# Copy files
WORKDIR /var/www/bookshop

# Copying just the needed files
COPY /www/composer.json /var/www/bookshop/composer.json
COPY /www/composer.lock /var/www/bookshop/composer.lock
COPY /www/vendor /var/www/bookshop/vendor

# To enable Apache's mod_rewrite module
RUN a2enmod rewrite

# To enable SSL protocol (needed to use https)
RUN a2enmod ssl

# Install and enable PHP extensions
RUN docker-php-ext-install mysqli pdo_mysql
RUN docker-php-ext-enable mysqli pdo_mysql

# Copy SSL certificate, key files, and Apache conf
COPY docker/apache-conf/localhost.pem /etc/ssl/certs/certificate.crt
COPY docker/apache-conf/localhost-key.pem /etc/ssl/private/server.key
COPY docker/apache-conf/bookshop.conf /etc/apache2/sites-available/bookshop.conf

RUN a2ensite bookshop.conf

# Restart Apache to apply changes
RUN service apache2 restart

# Update and upgrade packages
RUN apt-get update && apt-get upgrade -y

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install git and unzip (needed to fetch Zxcvbn)
RUN apt-get -y install git && apt-get -y install unzip

# Install zxcvbn and phpmailer
RUN composer install
RUN composer require bjeavons/zxcvbn-php
RUN composer require phpmailer/phpmailer
RUN composer update

# Cleanup unnecessary files
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
