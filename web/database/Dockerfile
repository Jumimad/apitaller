FROM ubuntu:latest

#install all the tools you might want to use in your container
RUN apt-get update
RUN sudo a2enmod rewrite
RUN sudo a2enmod headers
RUN apt-get install libapache2-mod-php
RUN apt-get install php-mysql -y
RUN apt-get install php-curl -y
RUN apt-get install php-xml -y
RUN service apache2 restart -y
RUN apt-get install vim -y
RUN mysql -u root examen < bd.sql
