FROM php:8.3-fpm-alpine AS builder

# Install system dependencies required for building PHP extensions and running Composer
RUN apk add --no-cache \
    bash \
    git \
    zip unzip \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    autoconf \
    dpkg-dev \
    dpkg \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconf \
    re2c

# Install common PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    zip \
    mbstring \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./app/ /var/www/html

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html


# ---------------- RUNTIME ----------------
FROM php:8.3-fpm-alpine AS runtime

# Install dependencies for extensions and Xdebug
RUN apk add --no-cache $PHPIZE_DEPS linux-headers \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        intl \
        zip \
        mbstring \
        opcache \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Composer (runtime convenience)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY --from=builder /var/www/html /var/www/html

# Change ownership (for Symfony cache/logs)
RUN chown -R www-data:www-data /var/www/html

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
