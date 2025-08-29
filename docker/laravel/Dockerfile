FROM php:8.2-fpm-bookworm

RUN rm -f /etc/apt/sources.list.d/* \
    && echo "deb https://deb.debian.org/debian bookworm main contrib non-free" > /etc/apt/sources.list \
    && echo "deb https://deb.debian.org/debian bookworm-updates main contrib non-free" >> /etc/apt/sources.list \
    && echo "deb https://security.debian.org/debian-security bookworm-security main contrib non-free" >> /etc/apt/sources.list

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    apt-utils ca-certificates gnupg lsb-release \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel

# Копируем весь проект сразу, чтобы artisan был доступен
COPY . .

# Устанавливаем зависимости
RUN composer install --no-dev --prefer-dist --optimize-autoloader

CMD ["php-fpm"]
