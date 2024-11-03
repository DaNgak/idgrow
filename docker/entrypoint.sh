#!/bin/bash

# Tunggu hingga database MySQL siap
echo "Waiting for MySQL..."
until nc -z -v -w30 idgrow_database 3306
do
    echo "Waiting for MySQL database connection..."
    sleep 5
done
echo "MySQL is up and running!"

# Jalankan perintah Laravel
php artisan key:generate
php artisan optimize:clear
php artisan migrate
php artisan db:seed

# Jalankan php-fpm
exec php-fpm
