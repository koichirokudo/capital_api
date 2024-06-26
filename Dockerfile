# syntax = docker/dockerfile:1
FROM php:8.1-fpm

# Install dependencies
RUN <<EOF
apt-get update
apt-get install -y --no-install-recommends \
    libpq-dev \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev
rm -rf /var/lib/apt/lists/*
docker-php-ext-install pdo_pgsql
groupadd nginx
adduser --system --disabled-login --disabled-password --no-create-home --ingroup nginx nginx
usermod -aG nginx nginx
EOF

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY settings/php.ini-production /usr/local/etc/php/php.ini

WORKDIR /var/www/api

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]

