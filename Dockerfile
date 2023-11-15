FROM php:8.2-apache

ARG NODE_VERSION=18
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

COPY . /var/www/html/

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update && \
    apt-get -y install libicu-dev \
    libzip-dev

RUN docker-php-ext-install -j$(nproc) intl zip pdo pdo_mysql

#RUN printf "\n"|pecl install -o -f redis \
#&&  rm -rf /tmp/pear \
#&&  docker-php-ext-enable redis

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

#RUN curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
#    	&& apt  install -y nodejs

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN composer require
#RUN npm install && npm run production

RUN a2enmod rewrite
