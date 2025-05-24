FROM php:7.2-apache

RUN a2enmod rewrite \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
