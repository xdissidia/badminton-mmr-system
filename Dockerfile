FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    vim \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

ENV API_PUBLIC_PATH /var/www/html/public

COPY ./storage/config/ipams.local.conf /etc/apache2/sites-available/ipams.local.conf

RUN rm /etc/apache2/sites-available/000-default.conf -rf
RUN ln -s /etc/apache2/sites-available/ipams.local.conf /etc/apache2/sites-enabled/ipams.local.conf

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql

RUN \
    apt-get update && \
    apt-get install libldap2-dev -y && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

RUN composer install --no-interaction --optimize-autoloader

EXPOSE 80

CMD ["apache2-foreground"]
