# Utilise l'image PHP avec Apache
FROM php:8.2-apache

# Active l'extension mysqli pour MySQL
RUN docker-php-ext-install mysqli

# Copie code source dans le container
COPY ./src/ /var/www/html/

# Active les réécritures d'URL (utile pour .htaccess)
RUN a2enmod rewrite

# Expose le port 80 dans le container
EXPOSE 80
