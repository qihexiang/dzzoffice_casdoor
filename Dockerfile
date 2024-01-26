FROM php:7.4-apache

COPY --chown=www-data:www-data ./ /var/www/html

RUN apt-get update \
&& apt-get install libzip-dev unzip -y \
&& apt-get clean all \
&& docker-php-source extract \
&& docker-php-ext-install zip && docker-php-ext-install mysqli \
&& docker-php-source delete

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
&& php composer-setup.php \
&& php -r "unlink('composer-setup.php');" \
&& php composer.phar require casdoor/casdoor-php-sdk
