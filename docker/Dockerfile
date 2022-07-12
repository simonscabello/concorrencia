FROM php:7.4-fpm-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync

RUN install-php-extensions mysqli opcache pdo_mysql

RUN apk update && apk add --no-cache nginx bash

RUN mkdir -p /var/www/app

WORKDIR /var/www/app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

COPY ./docker/entrypoint.sh ./entrypoint.sh

RUN composer install

RUN chmod -R 777 ./storage ./public ./bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN chmod +x ./entrypoint.sh

ENTRYPOINT ["./entrypoint.sh"]
