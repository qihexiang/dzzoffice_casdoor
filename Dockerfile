FROM php:7.4-apache

RUN apt-get update \
&& apt-get install libzip-dev unzip -y \
&& apt-get clean all \
&& docker-php-source extract \
&& docker-php-ext-install zip && docker-php-ext-install mysqli \
&& docker-php-source delete

COPY --chown=www-data:www-data ./ /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php \
&& php -r "unlink('composer-setup.php');" \
&& php composer.phar require casdoor/casdoor-php-sdk

ENTRYPOINT ["/var/www/html/entrypoint.sh"]
