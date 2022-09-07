FROM php:8.1-fpm-alpine
MAINTAINER Nafeeur Rahman <inafeeur@gmail.com>

ENV PECL_EXTENSIONS="pcov psr redis xdebug"
ENV PHP_EXTENSIONS="bz2 exif gd gettext intl pcntl pdo_mysql zip"

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS libtool \
    && apk add --no-cache bzip2-dev gettext-dev git icu icu-dev libintl libpng-dev libzip-dev mysql-client \

    && docker-php-source extract \
    && pecl channel-update pecl.php.net \
    && pecl install $PECL_EXTENSIONS \
    && cd /usr/src/php/ext/ \
    && docker-php-ext-enable $PECL_EXTENSIONS \
    && docker-php-ext-configure opcache --enable-opcache \

    && docker-php-ext-install -j "$(nproc)" $PHP_EXTENSIONS \

    && apk del -f .build-deps \
    && cd /usr/local/etc/php/conf.d/ \
    && pecl clear-cache \
    && docker-php-soruce delete \
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/doc/* /usr/share/man/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo

# Install dockerize so we can wait for containers to be ready
# ENV DOCKERIZE_VERSION 0.6.1

# RUN curl -s -f -L -o /tmp/dockerize.tar.gz https://github.com/jwilder/dockerize/releases/download/v$DOCKERIZE_VERSION/dockerize-linux-amd64-v$DOCKERIZE_VERSION.tar.gz \
#     && tar -C /usr/local/bin -xzvf /tmp/dockerize.tar.gz \
#     && rm /tmp/dockerize.tar.gz

# # Install Composer
# ENV COMPOSER_VERSION 2.1.5

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=$COMPOSER_VERSION

# # Install nodejs
# RUN curl -sL https://deb.nodesource.com/setup_14.x | bash

# RUN apt-get update \
#     && apt-get install -y --no-install-recommends \
#         libz-dev \
#         libpq-dev \
#         libjpeg-dev \
#         libpng-dev \
#         libssl-dev \
#         libzip-dev \
#         unzip \
#         zip \
#         nodejs \
#     && apt-get clean \
#     && pecl install redis \
#     && docker-php-ext-configure gd \
#     && docker-php-ext-configure zip \
#     && docker-php-ext-install \
#         gd \
#         exif \
#         opcache \
#         pdo_mysql \
#         pdo_pgsql \
#         pgsql \
#         pcntl \
#         zip \
#     && docker-php-ext-enable redis \
#     && rm -rf /var/lib/apt/lists/*;

# COPY ./docker/php/laravel.ini /usr/local/etc/php/conf.d/laravel.ini

WORKDIR /var/www/html/pathao
USER www-data

# RUN chown -R www-data:www-data .