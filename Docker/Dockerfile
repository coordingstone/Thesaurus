FROM php:7.4-apache
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
 