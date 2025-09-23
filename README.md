# Task Manager

[![Actions Status](https://github.com/NikolaiProgramist/php-project-57/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/NikolaiProgramist/php-project-57/actions) [![lint](https://github.com/NikolaiProgramist/php-project-57/actions/workflows/lint.yml/badge.svg)](https://github.com/NikolaiProgramist/php-project-57/actions/workflows/lint.yml) [![tests](https://github.com/NikolaiProgramist/php-project-57/actions/workflows/tests.yml/badge.svg)](https://github.com/NikolaiProgramist/php-project-57/actions/workflows/tests.yml) [![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57)<br>
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57) [![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57) [![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57) [![Coverage](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=coverage)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57)<br>
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=bugs)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57) [![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57) [![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=NikolaiProgramist_php-project-57&metric=duplicated_lines_density)](https://sonarcloud.io/summary/new_code?id=NikolaiProgramist_php-project-57)

## About

This is a minimalistic CRM system for managing the flow of tasks and personnel.

**See the web service:** [Task Manager](https://php-project-57-xp8o.onrender.com).

## Requirements

* PHP ^8.2
* Node ^20.19.0 || >=22.12.0
* npm

## Setup

1. Download this project:

    ```shell
    git clone https://github.com/NikolaiProgramist/php-project-57.git
    cd php-project-57
    ```

2. Select setup type:

   * [Quick Start](#-quick-start)
   * [Docker](#-docker)
   * [Local](#-local)

3. Follow this link: http://localhost:8000

### 🚀 Quick Start

```shell
make setup-start
```

### 🐋 Docker

Change the database environment variables in the `.env.example` as specified here:

```text
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=password
```

Run `docker-compose.yml`:

```shell
docker compose up
```

### ✏️ Local

Run setup command:

```shell
make setup
```

Run server:

```shell
make start
```

## License

There is currently no license. All rights reserved ©

## Support

You can [Create Issues](https://github.com/NikolaiProgramist/php-project-57/issues) to help improve the project. You can suggest your ideas, find bugs and errors. We will be grateful for your help!

## Stargazers over time

[![Stargazers over time](https://starchart.cc/NikolaiProgramist/php-project-57.svg?variant=adaptive)](https://starchart.cc/NikolaiProgramist/php-project-57)
