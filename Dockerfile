FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev git curl unzip \
    && docker-php-ext-install pdo pdo_pgsql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Копируем все файлы сразу
COPY . .

# Устанавливаем зависимости с отключенными скриптами
RUN composer install --no-dev --no-scripts

# Вручную запускаем необходимые команды
RUN composer dump-autoload --optimize

# Создаем .env и генерируем ключ
RUN cp .env.example .env
RUN php artisan key:generate

RUN touch database/database.sqlite

CMD ["bash", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
