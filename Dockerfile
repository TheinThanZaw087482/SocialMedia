# Use official PHP 8.2 with Apache
FROM php:8.2-apache

# Enable mod_rewrite for Apache (.htaccess support)
RUN a2enmod rewrite

# Optional: Install common PHP extensions (match your local)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all project files into Apache web root
COPY . /var/www/html/

# Set working directory (optional)
WORKDIR /var/www/html/

# Expose port 80 (Render handles this)
EXPOSE 80
