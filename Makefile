PORT ?= 8000
start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public
	php artisan migrate:fresh --force --seed
install:
	composer install
validate:
	composer validate
lint:
	composer exec --verbose phpcs -- --standard=PSR12 routes app tests
test:
	php artisan test
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
build:
	npm ci && npm run build
setup:
	cp .env.example .env
	composer install
	php artisan key:generate
	npm install
	npm ci
	npm run build
