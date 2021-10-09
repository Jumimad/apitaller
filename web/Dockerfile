FROM ubuntu:latest

RUN apt-get update
RUN sudo a2enmod rewrite
RUN sudo a2enmod headers
RUN apt-get install libapache2-mod-php
RUN apt-get install php-mysql -y
RUN apt-get install php-curl -y
RUN apt-get install php-xml -y
RUN service apache2 restart -y
RUN apt-get install vim -y

#change working directory to root of apache webhost
WORKDIR var/www/html/api

#copy your files, if you want to copy all use COPY . .
COPY index.php index.php

#now start the server
CMD ["apachectl", "-D", "FOREGROUND"]
