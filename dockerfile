FROM php:8.2-apache

COPY . /var/www/html/

RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf && \
    echo '<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html\n\
    </VirtualHost>' > /etc/apache2/sites-enabled/000-default.conf

EXPOSE ${PORT}

CMD ["apache2-foreground"]