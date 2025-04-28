#!/bin/sh

echo "Waiting for MySQL..."

while ! nc -z mysql 3306; do
  sleep 1
done

echo "Ready"

php artisan migrate
php artisan db:seed

exec "$@"
