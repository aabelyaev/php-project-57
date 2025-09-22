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

# Копируем только необходимые для сборки файлы
COPY package*.json composer.* ./
COPY resources/ resources/
COPY vite.config.js ./

# Устанавливаем и собираем
RUN npm ci && npm run build

# Теперь копируем все остальные файлы
COPY . .

RUN composer install --no-dev --no-scripts --optimize-autoloader
RUN cp .env.example .env && php artisan key:generate --force
RUN touch database/database.sqlite

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
