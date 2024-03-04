FROM php:apache

# To enable Apache's mod_rewrite module
RUN a2enmod rewrite

# To enable SSL protocol (needed to use https)
RUN a2enmod ssl

# Install and enable PHP extensions
RUN docker-php-ext-install mysqli pdo_mysql
RUN docker-php-ext-enable mysqli pdo_mysql

# Start Apache to apply changes and then check its status
RUN service apache2 start && service apache2 status

# Copy SSL certificate, key files, and Apache conf
COPY apache-conf/localhost.pem /etc/ssl/certs/certificate.crt
COPY apache-conf/localhost-key.pem /etc/ssl/private/server.key
COPY apache-conf/bookshop.conf /etc/apache2/sites-available/bookshop.conf

RUN a2ensite bookshop.conf

# Restart Apache to apply changes
RUN service apache2 restart

# Update and upgrade packages
RUN apt-get update && apt-get upgrade -y

# Cleanup unnecessary files
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
