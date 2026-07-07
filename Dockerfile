FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Active le module Apache pour les réécritures d’URL
RUN a2enmod rewrite

# msmtp sert de relai "sendmail" pour la fonction standard mail() de PHP,
# vers le conteneur mailpit (catcher SMTP local, cf. docker-compose.yml)
RUN apt-get update \
    && apt-get install -y --no-install-recommends msmtp msmtp-mta \
    && rm -rf /var/lib/apt/lists/*

COPY ./docker/msmtprc /etc/msmtprc
RUN chmod 644 /etc/msmtprc

COPY ./docker/php-mail.ini /usr/local/etc/php/conf.d/zz-mail.ini

COPY ./ /var/www/html/

# Donne les droits à Apache
RUN chown -R www-data:www-data /var/www/html

# Configure Apache pour lire le .htaccess
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
