FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy codebase
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
