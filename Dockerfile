FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

RUN a2enmod rewrite

# Copy application source
COPY . /var/www/html/

WORKDIR /var/www/html
