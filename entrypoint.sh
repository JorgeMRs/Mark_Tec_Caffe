#!/bin/bash

# Ejecutar el archivo server.php
php /var/www/cafesabrosos/src/server.php &

# Iniciar PHP-FPM
php-fpm
