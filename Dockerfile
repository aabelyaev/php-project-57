FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    git \
    curl \
    unzip
RUN docker-php-ext-install pdo pdo_pgsql zip

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка Node.js (более надежная версия)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

WORKDIR /app

# Копируем только необходимые файлы для установки зависимостей
COPY composer.json composer.lock ./
COPY package.json package-lock.json* ./

# Устанавливаем PHP зависимости
RUN composer install --no-dev --optimize-autoloader

# Устанавливаем Node.js зависимости
RUN npm ci --only=production

# Копируем остальные файлы
COPY . .

# Копируем .env.example если .env отсутствует
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Генерация ключа приложения
RUN php artisan key:generate

# Сборка фронтенда (если есть фронтенд)
RUN if [ -f "vite.config.js" ] || [ -f "webpack.mix.js" ]; then npm run build; else echo "No frontend build config found, skipping..."; fi

# Создаем SQLite базу если используется
RUN if [ ! -f database/database.sqlite ]; then touch database/database.sqlite; fi

CMD ["bash", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
