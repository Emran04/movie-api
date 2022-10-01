FROM php:8.1.7-fpm-buster

RUN docker-php-ext-install bcmath pdo_mysql

RUN apt-get update && apt-get install -y \
        git \
        zip \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-install zip

RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install \
    pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 9000
