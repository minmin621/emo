# Use the official PHP image with Apache
FROM php:8.2-apache

# Copy project files to container
COPY . /var/www/html/

# Expose ports
EXPOSE 80
EXPOSE 443
