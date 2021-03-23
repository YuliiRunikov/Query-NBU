FROM php:7.4-cli-buster

RUN apt update && apt install -y curl git zip

RUN pecl install xdebug

WORKDIR /app

COPY .. /app

RUN curl -L getcomposer.org/installer | php -- --filename=composer \
    && chmod +x ./composer \
    && ./composer install \
    && ./composer test -- --coverage-clover=/coverage.xml


