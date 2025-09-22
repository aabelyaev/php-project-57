FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Копируем файлы зависимостей first
COPY composer.json composer.lock package.json package-lock.json* ./

# Устанавливаем зависимости с отключенными плагинами
RUN composer install --optimize-autoloader --no-dev --no-plugins --no-scripts

# Копируем остальные файлы
COPY . .

# Устанавливаем Node.js зависимости
RUN npm ci

# Запускаем artisan команды отдельно
RUN composer run-script post-autoload-dump

# Создаем .env и генерируем ключ
RUN cp .env.example .env
RUN php artisan key:generate

# Собираем assets
RUN npm run build

# Создаем SQLite базу
RUN touch database/database.sqlite

# Настройка прав
RUN chmod -R 775 storage bootstrap/cache

CMD ["bash", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"]
