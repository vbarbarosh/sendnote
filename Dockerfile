FROM php:7.4-apache

RUN apt-get update
RUN apt-get install -q -y gawk msmtp mailutils

COPY docker.fs /

# https://stackoverflow.com/a/63977888/1478566
RUN chmod 600 /etc/msmtprc
RUN chown www-data:www-data /etc/msmtprc
RUN chown www-data:www-data /var/log/msmtp.log

COPY src /var/www/html/
