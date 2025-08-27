# PHP 8.3 CLI (compatível com openspout ^4.23)
FROM php:8.3-cli

# Dependências do sistema + SQLite + ICU (p/ intl) + ZIP
RUN apt-get update \
 && apt-get install -y git unzip libzip-dev libicu-dev sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite zip intl

# Composer (permitir rodar como root no build)
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Código
WORKDIR /app
COPY . /app

# Instalar deps do Laravel (sem dev) e otimizar autoload
RUN composer install --no-dev -o

# Permissões (storage e cache)
RUN chmod -R 775 storage bootstrap/cache || true

# Porta do servidor artisan
EXPOSE 10000

# Ao subir o container: migra e inicia o servidor
CMD php artisan migrate --force \
 && php artisan storage:link || true \
 && php artisan filament:optimize \
 && php artisan route:cache \
 && php artisan config:cache \
 && php artisan view:cache \
 && php artisan serve --host 0.0.0.0 --port 10000

