FROM php:8.2-cli

WORKDIR /var/www/html
COPY . .
RUN docker-php-ext-install mysqli
EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/src"]
