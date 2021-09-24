#!/bin/bash

# Run composer install
composer install

# Generate app key and clear cache
php artisan key:generate
php artisan config:clear

# Run migrations
# php artisan migrate:fresh --seed

php artisan storage:link


#TODO!! uncomment this line if you want to ensure that another service is up before the api
# until nc -z db 3306; do sleep 1; echo "Waiting for DB to come up..."; done

# Start PHP-FPM
php-fpm
