FROM php:fpm-alpine

RUN apk add --update --no-cache \
    build-base linux-headers shadow vim curl \
    libzip-dev libmcrypt-dev libpng-dev libjpeg-turbo-dev \
    libxml2-dev icu-dev postgresql-dev curl-dev libmemcached-dev \
    composer \
    php81 \
    php81-dev \
    php81-fpm \
    php81-common \
    php81-pdo \
    php81-pdo_pgsql  \
    php81-mbstring \
    php81-xml \
    php81-xmlwriter \
    php81-openssl \
    php81-json \
    php81-phar \
    php81-zip \
    php81-gd \
    php81-dom \
    php81-session \
    php81-zlib \
    php81-ctype \
    php81-curl \
    php81-dom \
    php81-opcache \
    php81-tokenizer


RUN docker-php-ext-install pdo pdo_pgsql #xml xmlwriter
# RUN docker-php-ext-enable pdo pdo_mysql pdo_pgsql xml xmlwriter

# fix dublicates

#RUN apt-get update \
#&& apt-get install -y zip unzip libzip-dev vim default-mysql-client libicu-dev libxml2-dev libpng-dev\
#&& docker-php-ext-configure intl \
#&& docker-php-ext-install zip pdo_mysql intl soap zip bcmath exif gd 

# TODO review
# docker-php-ext vs inistall-php-ext

#ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

#RUN chmod +x /usr/local/bin/install-php-extensions && \
#    install-php-extensions gd xdebug soap zip bcmath exif gd 

RUN yes | pecl install xdebug 

RUN docker-php-ext-enable xdebug pdo  pdo_pgsql # xml xmlwriter

COPY ./docker/php/php.ini /etc/php81/php.ini
COPY ./docker/php/fpm.conf /etc/php81/php-fpm.d/www.conf

WORKDIR /runtime

RUN usermod -u 1000 www-data

#COPY --chown=www-data:www-data ./src/tmp /runtime

RUN chown -R www-data:www-data /runtime

EXPOSE 9000

USER www-data

CMD ["php-fpm"]

#RUN curl -sS https://getcomposer.org/installer | php \
#&& mv composer.phar /bin/composer

#RUN composer install -noa