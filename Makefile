PORT ?= 8000

start:
	php artisan serve --host=0.0.0.0 --port=$(PORT)

setup:
	composer install
	cp -n .env.example .env
	touch database/database.sqlite
	php artisan key:generate
	php artisan migrate
	php artisan db:seed
	npm ci
	npm run build

setup-start:
	make setup
	make start

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

compose-db:
	docker compose exec db psql -U postgres

install:
	composer install

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text

lint:
	./vendor/bin/phpcs --standard=PSR12 app routes database lang tests bootstrap/app.php

lint-fix:
	./vendor/bin/phpcbf --standard=PSR12 app routes database lang tests bootstrap/app.php
