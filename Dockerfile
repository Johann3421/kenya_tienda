FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        cron \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        libzip-dev \
        libicu-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        zip \
    && a2enmod rewrite headers expires \
    && sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --no-autoloader --ignore-platform-reqs

COPY . .
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/99-kenya.ini
COPY docker/entrypoint.sh /usr/local/bin/kenya-entrypoint

RUN cp -r storage/app/public /var/www/initial_storage_public

RUN composer dump-autoload --optimize \
    && chmod +x /usr/local/bin/kenya-entrypoint \
    && mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Cron para el scheduler de Laravel (cada lunes sync:fichas)
COPY docker/scheduler-cron /etc/cron.d/kenya-scheduler
RUN chmod 0644 /etc/cron.d/kenya-scheduler

EXPOSE 80

# ── Python para enriquecer_procesadores.py / enriquecer_endpoint.py ──────────
RUN apt-get update && apt-get install -y --no-install-recommends \
        python3 \
        python3-pip \
        python3-venv \
    && rm -rf /var/lib/apt/lists/*

RUN pip3 install --break-system-packages \
        requests \
        anthropic \
        psycopg2-binary \
        python-dotenv
# ─────────────────────────────────────────────────────────────────────────────

# ── Node.js & Chromium para Scraper de Nanoreview ────────────────────────────
RUN apt-get update && apt-get install -y --no-install-recommends curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs chromium \
    && rm -rf /var/lib/apt/lists/*

COPY scraper /var/www/scraper
RUN cd /var/www/scraper && npm install
# ─────────────────────────────────────────────────────────────────────────────

ENTRYPOINT ["kenya-entrypoint"]
CMD ["apache2-foreground"]
