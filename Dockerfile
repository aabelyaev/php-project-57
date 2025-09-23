FROM php

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev
RUN docker-php-ext-install pdo pdo_pgsql zip
# RUN docker-php-ext-configure pdo pdo_pgsql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN curl -fsSL https://nodistro.nodesource.com/setup_22.12.0 | bash -
RUN apt-get install -y nodejs npm

WORKDIR /app

COPY . .

RUN composer install
RUN cp .env.example .env
RUN php artisan key:generate
RUN npm ci
RUN npm run build

RUN > database/database.sqlite

CMD ["bash", "-c", "php artisan migrate:fresh --force --seed && make start"]
