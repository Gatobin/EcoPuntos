# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones comunes para PHP (si necesitas MySQL/PostgreSQL)
RUN docker-php-ext-install pdo pdo_mysql

# Habilita el módulo rewrite de Apache (para URLs amigables)
RUN a2enmod rewrite

# Copia todo tu código al directorio de Apache
COPY . /var/www/html/

# Puerto que Render usa por defecto para Web Services
EXPOSE 10000

# Comando para iniciar Apache
CMD ["apache2-foreground"]