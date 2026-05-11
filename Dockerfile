FROM php:8.2-apache-alpine

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli


COPY . /var/www/html/


RUN chown -R www-data:www-data /var/www/html

EXPOSE 80 
