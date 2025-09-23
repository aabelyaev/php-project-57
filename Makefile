PORT ?= 8000

start:
	php artisan serve --host=0.0.0.0 --port=$(PORT)

setup:
	cp .env.example .env
	composer install
	php artisan key:generate
	npm install
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
	./vendor/bin/pint app routes database resources lang tests --test --verbose

lint-fix:
	./vendor/bin/pint app routes database resources lang tests
