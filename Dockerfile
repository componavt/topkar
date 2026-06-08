FROM php:7.4-apache

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Включить mod_rewrite
RUN a2enmod rewrite

# Установить зависимости и PHP-расширения (Laravel 8 требует больше)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip \
    git \
    curl \
    mariadb-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Копируем конфиг Apache
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Закомментировать глобальный DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|#DocumentRoot /var/www/html|g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
