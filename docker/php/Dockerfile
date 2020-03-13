FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
        libicu-dev \
    && docker-php-ext-install \
        intl \
        opcache \
    && docker-php-ext-enable \
        intl \
        opcache \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install intl

RUN apt-get install -y zip unzip zlib1g-dev libpng-dev libzip-dev
RUN docker-php-ext-install gd zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/bin && \
    chmod +x /usr/bin/composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=phpstorm" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=127.0.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini

# Permission fix
RUN usermod -u 1000 www-data
