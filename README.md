Creative
========

A generic way to learn and teach

## Server Requirements

The Laravel framework has a few system requirements:

* PHP >= 5.4
* MCrypt PHP Extension

## Quick Start

    composer install
    php artisan serve

## css minify

    cd PATH/TO/root_dir
    npm install
    grunt cssmin

## Queue Worker

    php artisan queue:listen

## Migration

    php artisan db:seed
    php artisan migrate
    composer update
    composer dump-autoload

## Apply PSR-1 PSR-2

    php ./vendor/bin/php-cs-fixer fix app/controllers/ --level=all
