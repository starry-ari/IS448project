FROM php:8.2-apache

RUN docker-php-ext-install mysqli

# Copy the app files from public/src/
COPY public/src/ /var/www/html/

# Copy index.php from root into the same web root
COPY index.php /var/www/html/

RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf && \
    echo '<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html\n\
    </VirtualHost>' > /etc/apache2/sites-enabled/000-default.conf

EXPOSE ${PORT}

CMD ["apache2-foreground"]