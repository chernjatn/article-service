FROM php:8.2-apache

ARG NODE_VERSION=18
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

COPY . /var/www/html/

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update && \
    apt-get -y install libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    cron

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) intl zip pdo pdo_mysql exif gd

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN composer require
RUN composer dump-autoload -o

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN a2enmod rewrite
RUN apt clean

RUN php artisan storage:link
RUN echo "* * * * * cd /var/www/html && /usr/local/bin/php artisan schedule:run > /tmp/cron.log 2> /tmp/cron.log" > /etc/cron.d/laravel && crontab /etc/cron.d/laravel
RUN sed -i 's/^exec /service cron start\nexec /' /usr/local/bin/docker-php-entrypoint
