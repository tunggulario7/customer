FROM php:7.4.3-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    && apt install -y zlib1g-dev libmemcached-dev git libicu-dev zip libzip-dev zip libpq-dev \
    && apt-get install -y  software-properties-common \
    && pecl install apcu


# Install php extensions.
RUN docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache

WORKDIR /var/www

COPY ./php.ini /usr/local/etc/php/

## RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer