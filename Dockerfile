FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Копируем файлы зависимостей first (для кэширования)
COPY composer.json composer.lock package.json package-lock.json* ./

# Устанавливаем зависимости
RUN composer install --optimize-autoloader --no-dev
RUN npm ci

# Копируем весь проект
COPY . .

# Создаем .env и генерируем ключ
RUN cp .env.example .env && php artisan key:generate

# Собираем assets
RUN npm run build

# Настройка базы данных и прав
RUN touch database/database.sqlite
RUN chmod -R 775 storage bootstrap/cache

CMD ["bash", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"]
