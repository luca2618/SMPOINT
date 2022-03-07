FROM ubuntu:latest

# Use premade container with PHP
FROM php:8.0-apache

RUN apt-get update
#RUN apt upgrade
#install nano og mysqli
RUN apt-get -y install nano
RUN docker-php-ext-install mysqli
# Copy everything in ./ of the project to the WORKDIR
COPY ./ /var/www/point.smkid.dk
#update configs
COPY ./setup/point.smkid.dk.conf /etc/apache2/sites-available/point.smkid.dk.conf
COPY ./setup/mime.types /etc/mime.types
RUN chmod -R 755 /var/www/point.smkid.dk
#?????
RUN chown -R $USER:$USER /var/www/point.smkid.dk
#Rewrite aktive site
RUN a2enmod rewrite
RUN a2dissite 000-default
RUN a2ensite point.smkid.dk
RUN service apache2 restart
# Expose Port 80
EXPOSE 80
