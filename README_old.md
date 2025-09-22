[![hexlet-check](https://github.com/vsev92/php-project-57/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/vsev92/php-project-57/actions/workflows/hexlet-check.yml)

[![linter](https://github.com/vsev92/php-project-57/actions/workflows/linterAndTests.yml/badge.svg)](https://github.com/vsev92/php-project-57/actions/workflows/linterAndTests.yml)

[![Maintainability](https://api.codeclimate.com/v1/badges/d2025ca199014e093771/maintainability)](https://codeclimate.com/github/vsev92/php-project-57/maintainability)

[![Test Coverage](https://api.codeclimate.com/v1/badges/d2025ca199014e093771/test_coverage)](https://codeclimate.com/github/vsev92/php-project-57/test_coverage)

## Simple task manager
Web service for planning and setting work tasks

## Link to production:
https://php-project-57-10vm.onrender.com/

## Prerequisites
* Linux
* PHP >=8.3
* Composer
* Make
* PostgeSQL

## Setup project
```bash
git clone git@github.com:vsev92/php-project-57.git
cd  php-project-57
make install
```
## Setup database
1. create empty PostgreSql database
2. Copy .env file from .env.example
## 
```bash
cp .env.example .env
```
3. Customize enviroment variables
    DB_CONNECTION
    DB_HOST
    DB_PORT
    DB_DATABASE
    DB_USERNAME
    DB_PASSWORD
  to connect to created database

## Run frontend
```bash
npm run dev
```
## Run web server
```bash
make start
```
then web service is avaible on 127.0.0.1:8000
