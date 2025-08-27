# PHP 8.2 CLI
FROM php:8.2-cli

# Dependências do sistema e SQLite
RUN apt-get update \
 && apt-get install -y git unzip libzip-dev sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Código
WORKDIR /app
COPY . /app

# Instalar deps do Laravel (sem dev) e otimizar autoload
RUN composer install --no-dev -o

# Permissões (storage e cache)
RUN chmod -R 775 storage bootstrap/cache || true

# Porta que o artisan serve vai usar
EXPOSE 10000

# Ao subir o container: migra e inicia o servidor
CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port 10000
