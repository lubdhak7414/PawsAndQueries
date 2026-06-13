# PawsAndQueries — local development image
FROM php:8.2-apache

# The app talks to MySQL through PDO.
RUN docker-php-ext-install pdo pdo_mysql

# Copy the application into the image (also bind-mounted in docker-compose for
# live editing during development).
COPY . /var/www/html/

EXPOSE 80
