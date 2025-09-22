FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Сначала генерируем ключ БЕЗ базы данных
RUN cp .env.example .env
RUN php artisan key:generate --force

# Затем устанавливаем зависимости
RUN composer install --no-dev --no-scripts --optimize-autoloader
RUN npm ci && npm run build

# Создаем базу и выполняем миграции
RUN touch database/database.sqlite
RUN php artisan migrate --force

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
