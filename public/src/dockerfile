FROM php:8.2-apache

RUN docker-php-ext-install mysqli

# Copy everything from one place
COPY public/src/ /var/www/html/

RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf && \
    echo '<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html\n\
    </VirtualHost>' > /etc/apache2/sites-enabled/000-default.conf

EXPOSE ${PORT}

CMD ["apache2-foreground"]