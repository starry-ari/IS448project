# Use official PHP + Apache image
FROM php:8.2-apache

# Copy all project files into Apache web root
COPY . /var/www/html/

# Enable rewrite module (optional)
RUN a2enmod rewrite

# Expose web port
EXPOSE 80
