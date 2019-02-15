FROM php:7.1-apache

EXPOSE 80

CMD [ "echo", "hello" ]

# Install any needed packages
RUN apt-get update \
    && apt-get -y upgrade \
    && a2enmod rewrite
