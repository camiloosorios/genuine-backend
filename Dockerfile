# Usa la imagen de Laravel Sail como base (en este caso, PHP 8.4)
FROM sail-8.4/app:latest

# Define variables de entorno si lo requieres (opcional)
ARG WWWGROUP
ARG WWWUSER

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos de la aplicación
COPY . .

# Instalar dependencias de Composer (se asume que ya tienes composer en la imagen base)
RUN composer install --no-dev --optimize-autoloader

# Cambiar permisos de storage y bootstrap/cache
RUN chown -R ${WWWUSER}:${WWWGROUP} /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Exponer el puerto 80 (o el que definas en la variable APP_PORT)
EXPOSE 80

# Comando de inicio (se podría ajustar según la entrada de Sail)
CMD ["php-fpm"]
