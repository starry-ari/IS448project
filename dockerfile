FROM php:8.2-apache

RUN docker-php-ext-install mysqli

# Copy app files
COPY public/src/ /var/www/html/
COPY index.php /var/www/html/index.php

# Enable mod_rewrite
RUN a2enmod rewrite

# Configure Apache to listen on Railway's PORT and allow .htaccess
RUN echo "Listen ${PORT}" > /etc/apache2/ports.conf && \
    echo '<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-enabled/000-default.conf

# Create .htaccess file (if your app doesn't already have one)
RUN echo 'RewriteEngine On\n\
RewriteCond %{REQUEST_FILENAME} !-f\n\
RewriteCond %{REQUEST_FILENAME} !-d\n\
RewriteRule ^ index.php [QSA,L]\n' > /var/www/html/.htaccess

EXPOSE ${PORT}

CMD ["apache2-foreground"]