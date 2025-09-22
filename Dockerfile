FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev
RUN docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

COPY . .

# Копируем .env.production или создаем .env
COPY .env.example .env

# Генерируем ключ приложения
RUN php artisan key:generate

# Устанавливаем зависимости
RUN composer install --optimize-autoloader --no-dev
RUN npm ci
RUN npm run build

# Создаем SQLite базу данных
RUN touch database/database.sqlite

# Настройка прав
RUN chmod -R 775 storage bootstrap/cache

CMD ["bash", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"]
