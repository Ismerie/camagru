FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Active le module Apache pour les réécritures d’URL
RUN a2enmod rewrite

COPY ./ /var/www/html/

# Donne les droits à Apache
RUN chown -R www-data:www-data /var/www/html

# Configure Apache pour lire le .htaccess
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
