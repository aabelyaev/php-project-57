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

# 1. Сначала устанавливаем зависимости Composer
RUN composer install --no-dev --no-scripts --optimize-autoloader

# 2. Затем генерируем ключ (теперь vendor/autoload.php существует)
RUN cp .env.example .env
RUN php artisan key:generate --force

# 3. Устанавливаем и собираем фронтенд
RUN npm ci
RUN npm run build

# 4. Создаем базу и выполняем миграции
RUN touch database/database.sqlite
RUN php artisan migrate --force

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
