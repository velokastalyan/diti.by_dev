FROM php:5.6-apache

# Install mysql extension (deprecated in 7.0, removed in 8.0)
RUN docker-php-ext-install mysql mysqli pdo pdo_mysql

# Enable mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Update apache config to allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Remove default index.html so index.php is served
RUN rm -f /var/www/html/index.html
