# Usar la imagen oficial de PHP con soporte para PHP-FPM
FROM php:8.3-fpm

# Instalar extensiones necesarias, incluyendo mysqli
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd

# Instalar Composer (gestión de dependencias)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copiar el código fuente de la aplicación
COPY . /var/www/cafesabrosos

# Establecer el directorio de trabajo
WORKDIR /var/www/cafesabrosos

# Asignar permisos correctos
RUN chown -R www-data:www-data /var/www/cafesabrosos \
    && chmod -R 755 /var/www/cafesabrosos

RUN echo "display_errors = Off" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "display_startup_errors = Off" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT" >> /usr/local/etc/php/conf.d/custom.ini

# Instalar dependencias con Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto para PHP-FPM
EXPOSE 9000

# Comando por defecto
CMD php /var/www/cafesabrosos/src/server.php & php-fpm
