# Lite API


[![Build Status](https://img.shields.io/travis/soarecostin/lite-api/master.svg?style=flat-square)](https://travis-ci.org/soarecostin/lite-api)
[![Quality Score](https://img.shields.io/scrutinizer/g/soarecostin/lite-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/soarecostin/lite-api)
[![Build Status](https://scrutinizer-ci.com/g/soarecostin/lite-api/badges/build.png?b=master)](https://scrutinizer-ci.com/g/soarecostin/lite-api/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/soarecostin/lite-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/soarecostin/lite-api/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/soarecostin/lite-api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/soarecostin/lite-api/?branch=master)
[![StyleCI](https://styleci.io/repos/234064241/shield)](https://styleci.io/repos/234064241)

## Demo

* You can access a demo of this API at https://lite-api.dev.soa.re (this API uses authentication and will require a valid token - see below on how you can get a token)
* You can access a demo UI for this API at https://lite-ui.dev.soa.re (separate Nuxt.js project that you can access in the [soarecostin/lite-ui](https://github.com/soarecostin/lite-ui) repo) 

## Setup

#### Requirements

This project uses Laravel v6.11 and requires PHP >= 7.2 and the PHP `intl` extension.

#### Installation

Clone this repository
```
git clone git@github.com:soarecostin/lite-api.git
```

Inside the project directory, install the project dependencies
```
composer install
```

#### Environment configuration

Copy the `.env.example` file to `.env`
```
cp .env.example .env
```

Fill in the `DB_*` variables in the `.env` file to a local database

#### Database migration

Migrate and seed the database using the Laravel artisan command
```
php artisan migrate:fresh --seed
```

#### Initialize the JWT Auth secret

This project uses the `tymondesigns/jwt-auth` package for authentication using JWT tokens, which needs a secret to be generate and added to the `.env` file. You can generate the secret by running
```
php artisan jwt:secret
```
This will update your .env file with something like JWT_SECRET=foobar

#### Local development server

If you have PHP installed locally and you would like to use PHP's built-in development server to serve your application, you can use the serve Artisan command.
```
php artisan serve
```
This command will start a development server at http://localhost:8000

## Testing

In order to test the API, run
```
composer test
```
If you are using Windows and the above command returns an error, you can simply run `vendor\bin\phpunit`

## Authentication

This API uses two auth guards for authentication: `jwt` for session authentication and `airlock` for personal access tokens.

### JWT Tokens
Using the [JWT Auth](https://github.com/tymondesigns/jwt-auth) package, when attempting login to the API from a third party app (UI demo), the API will issue a short-lived JSON Web Token that the app will use to authenticate any following requests. When the token expires, the user is prompted to login again.

### Personal Access Tokens
This API issues personal access tokens using the [Laravel Airlock](https://github.com/laravel/airlock) package. Personal access tokens function like ordinary OAuth access tokens. They can be used to authenticate to the API over Basic Authentication (the token should be included in the `Authorization` header as a `Bearer` token).

Once logged in to the UI app, look for "Developer settings" in the top nav. From there, you will be able to create and remove personal access tokens.
