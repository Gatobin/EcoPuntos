# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones comunes para PHP
RUN docker-php-ext-install pdo pdo_mysql

# Habilita el módulo rewrite de Apache
RUN a2enmod rewrite

# Crea el directorio data y establece permisos ANTES de copiar los archivos
RUN mkdir -p /var/www/html/data && \
    chown -R www-data:www-data /var/www/html/data && \
    chmod -R 775 /var/www/html/data

# Copia todo tu código al directorio de Apache
COPY . /var/www/html/

# Asegura permisos después de copiar (para archivos existentes)
RUN chown -R www-data:www-data /var/www/html/data && \
    chmod -R 775 /var/www/html/data

# Puerto que Render usa por defecto
EXPOSE 10000

# Comando para iniciar Apache
CMD ["apache2-foreground"]