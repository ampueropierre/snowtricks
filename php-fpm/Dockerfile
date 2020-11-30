FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer --install-dir=/usr/bin \
    && chmod +x /usr/bin/composer \
    && php -r "unlink('composer-setup.php');" \
    && composer --version \