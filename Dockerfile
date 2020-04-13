FROM php:7.4-apache

WORKDIR /var/www/http

RUN apt-get update

# 1. development packages
RUN apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ \
    nodejs \
    npm
RUN apt-get -y install gcc make autoconf libc-dev pkg-config

# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    pgsql \
    bcmath \
    opcache \
    calendar \
    pdo_pgsql \
    zip

RUN pecl install mcrypt
RUN docker-php-ext-enable mcrypt

# 5. composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY src/package.json .
COPY src/package-lock.json .
RUN npm i -g npm
RUN npm install

COPY src/composer.lock src/composer.json ./
COPY src/database ./database
COPY src/. .
RUN composer install

RUN npm run prod

# Or do this
COPY ownstoragefiles.sh .
RUN chown -R www-data:www-data \
    /var/www/html/public/* \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# RUN mv .env.prod .env
COPY .env .
RUN php ./artisan optimize

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
