# ---------------------------------------------------------------
# Smart Greenhouse — API Symfony 7.4
# Build : docker build -t greenhouse-api .
# Run   : docker run -p 8000:10000 greenhouse-api
# Render: le conteneur DOIT écouter sur $PORT (10000 par défaut)
# ---------------------------------------------------------------
FROM php:8.4-cli

# Extensions PHP : Symfony + Doctrine (PostgreSQL) + intl
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libpq-dev \
    && docker-php-ext-install -j"$(nproc)" intl pdo_pgsql opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer (gestionnaire de paquets PHP)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 1) Dépendances d'abord : ce layer est mis en cache tant que composer.lock ne change pas
COPY backend/composer.json backend/composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction

# 2) Code de l'application
COPY backend/ .

# 3) Autoloader optimisé + répertoires de cache Symfony
RUN composer dump-autoload --optimize --no-scripts \
    && mkdir -p var/cache var/log \
    && chmod -R 777 var

# Sur Render, APP_ENV=prod est le défaut ; surchargeable par docker-compose
ENV APP_ENV=prod \
    APP_DEBUG=0 \
    PORT=10000

EXPOSE 10000

# Serveur PHP intégré, écoute sur $PORT (variable imposée par Render)
CMD ["sh", "-c", "php -d memory_limit=256M -S 0.0.0.0:${PORT} -t public public/router.php"]
