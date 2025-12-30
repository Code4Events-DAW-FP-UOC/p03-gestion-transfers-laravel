FROM php:8.4.13-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    git \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
