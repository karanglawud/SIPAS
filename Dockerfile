FROM php:8.4-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev

# Install PHP extensions needed by Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        bcmath \
        gd \
        zip \
        opcache \
        pcntl \
        intl

# Copy official Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files (public/build Vite assets are already precompiled)
COPY . .

# Install PHP dependencies without dev packages
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chmod +x start.sh

EXPOSE 8080

CMD ["bash", "start.sh"]
