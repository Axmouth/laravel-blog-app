FROM php:7.4-fpm

WORKDIR /var/www/http

RUN apt-get update

# development packages
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libpq-dev \
    nodejs \
    npm \
    libonig-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    pgsql \
    bcmath \
    opcache \
    calendar \
    pdo_pgsql \
    zip \
    mbstring \
    exif \
    pcntl

RUN pecl install mcrypt
RUN docker-php-ext-enable mcrypt
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

# start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
COPY src/package.json .
COPY src/package-lock.json .
RUN npm i -g npm
RUN npm install
RUN npm audit fix


# Copy existing application directory contents
COPY src .

RUN composer install

RUN npm run prod

# Or do this
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# RUN mv .env.prod .env
COPY .env .

RUN php ./artisan cache:clear
RUN php ./artisan route:clear
RUN php ./artisan config:clear
RUN php ./artisan view:clear
RUN php ./artisan optimize
RUN php ./artisan storage:link

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
