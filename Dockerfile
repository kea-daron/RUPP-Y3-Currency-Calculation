# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable mod_rewrite (required for clean URLs)
RUN a2enmod rewrite

# Install required system packages for PostgreSQL PDO
RUN apt-get update && apt-get install -y libpq-dev

# Install extensions: pdo_pgsql (PostgreSQL) instead of MySQL
RUN docker-php-ext-install pdo pdo_pgsql

# Set the working directory
WORKDIR /var/www/html

# Copy all project files to the container
COPY . .

# Set permissions (optional but good practice)
RUN chown -R www-data:www-data /var/www/html

# Apache config for URL rewriting
RUN echo '<Directory /var/www/html/>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf
