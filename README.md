## Bintracker example api

This is the api repository for the example 'bintracker' api for Fruitbat/Cloverhitch projects.

## Install
Install necessary modules:

* `imagick`
* `mbstring`

Install composer dependencies

```shell
composer install
```

Create mysql database and user

```shell
create database bintracker_local
create user 'bintracker_user'@'%' identified by '405RueSainte-CatherineEst'
grant all privileges on bintracker_local.* to 'bintracker_user'@'%';
flush privileges
```

Configure `.env` 

```shell
DB_DATABASE=bintracker_local
DB_USERNAME=bintracker_user
DB_PASSWORD=405RueSainte-CatherineEst
```

Minimum PHP version is 7.4

## Run tests
All tests are integration tests that run against the database.

### Configure phpunit.xml
Configure your `phpunit.xml` to point to your testing database

```xml
<server name="DB_HOST" value="127.0.0.1" />
<server name="DB_USER" value="<user>" />
<server name="DB_PASSWD" value="<password>" />
<server name="DB_DBNAME" value="<name of database>" />
```

### Confirm your environment
In your `.env` file make sure that `APP_ENV` is either `local` or `testing`

```shell
APP_ENV=testing
```

### Ensure your swaggers are built
Tests validate the returned data structure against the swagger documentation. For this to work your `swagger.yaml` file must be built.

```shell
cd swagger
./buildswagger.sh
```

The file `swaggers/upload/openapi.json` must exist.

### Run the tests
You can run all tests

```shell
php ./artisan test
```

Or just one

```shell
php ./artisan test tests/Feature/getUnitsTest.php
```

If `xdebug` is installed and configured, code coverage report can be generated

```shell
php ./artisan test --coverage-html /path/to/directory
```

## Run Larastan
You can run static analysis with [larastan](https://github.com/nunomaduro/larastan)

To install

```shell
composer require nunomaduro/larastan --dev
```

Configuration is found in `phpstan.neon`. Default level is 6.

To run larastan, use the convenience script:

```shell
./larastan
```
