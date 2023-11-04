FROM php:8.1-fpm
COPY composer.lock composer.json /var/www/
WORKDIR /var/www

RUN pecl install redis && docker-php-ext-enable redis
RUN apt-get update
RUN apt-get install -y build-essential \
    curl  \
    zlib1g-dev  \
    libzip-dev  \
    libpng-dev  \
    libonig-dev  \
    libxml2-dev  \
    libjpeg-dev  \
    libfreetype6-dev  \
    iputils-ping  \
    procps
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd sockets
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug && docker-php-ext-enable xdebug

#RUN apt install -y software-properties-common
#RUN apt install -y gnupg
#RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | gpg --dearmor -o /usr/share/keyrings/nodesource-archive-keyring.gpg
#RUN apt install -y nodejs
#RUN apt install -y  npm

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
COPY . /var/www
COPY --chown=www:www . /var/www
USER www

RUN cd /var/www && composer install #&& php artisan storage:link
#RUN npm install && npm run dev

CMD ["php-fpm"]
