FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    unzip \
    zip \
    icu-dev \
    libzip-dev \
    sqlite-dev \
    oniguruma-dev \
  && docker-php-ext-install \
    intl \
    pdo \
    pdo_sqlite \
    opcache \
    zip \
  && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zzz-local.ini

WORKDIR /app
