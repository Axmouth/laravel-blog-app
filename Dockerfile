FROM php:7.4-apache

COPY composer.lock composer.json /var/www/

COPY database /var/www/database

WORKDIR /var/www

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
    #    mbstring \
    pdo_pgsql \
    zip

RUN pecl install mcrypt
RUN docker-php-ext-enable mcrypt

# 5. composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
#    #    && php -r "if (hash_file('SHA384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
#    && php composer-setup.php \
#    && php -r "unlink('composer-setup.php');" \
#    && php composer.phar install --no-dev --no-scripts \
#    && rm composer.phar


# 6. we need a user with the same UID/GID with host user
# so when we execute CLI commands, all the host file's ownership remains intact
# otherwise command from inside container will create root-owned files and directories
#ARG uid
#RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
#RUN mkdir -p /home/devuser/.composer && \
#    chown -R devuser:devuser /home/devuser

WORKDIR /var/www/html
COPY package.json .
COPY package-lock.json .
RUN npm i -g npm
RUN npm install
COPY composer.json .
COPY composer.lock .
RUN composer install
COPY . .
RUN npm run prod

# Or do this
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# RUN mv .env.prod .env
RUN php artisan optimize

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
