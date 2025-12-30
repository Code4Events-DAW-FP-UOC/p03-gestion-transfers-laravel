#!/bin/bash

echo "Esperando a que la base de datos se estabilice..."
sleep 5 

if [ ! -d "vendor" ]; then
    echo "Instalando dependencias con Composer..."
    composer install --no-interaction --optimize-autoloader
fi

if [ ! -f ".env" ]; then
    echo "Creando archivo .env..."
    cp .env.example .env
fi

echo "Generando clave de aplicación..."
php artisan key:generate --no-interaction --force


echo "Ejecutando migraciones y seeds..."
php artisan migrate --force
php artisan db:seed --force


echo "Ajustando permisos de carpetas..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Arrancando Apache..."
exec apache2-foreground