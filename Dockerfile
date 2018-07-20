FROM php:7.1-apache
LABEL maintainer="Jeff Vandenberg <jeffvandenberg@gmail.com>"
USER root

ARG BUILD

# Get Composer
RUN curl -o /tmp/composer.phar -sSL https://getcomposer.org/download/1.6.3/composer.phar

# clean files
RUN rm -rf /var/www && mkdir -p /var/www && chown www-data:www-data /var/www

# copy files
COPY --chown=www-data:www-data class /var/www/class
COPY --chown=www-data:www-data config /var/www/config
COPY --chown=www-data:www-data lib /var/www/lib
COPY --chown=www-data:www-data src /var/www/src
COPY --chown=www-data:www-data tmp /var/www/tmp
COPY --chown=www-data:www-data tools /var/www/tools
COPY --chown=www-data:www-data webroot /var/www/webroot

COPY --chown=www-data:www-data composer.json /var/www/
COPY --chown=www-data:www-data composer.lock /var/www/
COPY --chown=www-data:www-data package.json /var/www/

RUN echo "${BUILD}" > /var/www/build_number && cat /var/www/build_number

# Append chat JS files
RUN mkdir -p /var/www/webroot/chat/js/cache
RUN cat /var/www/webroot/chat/js/*.js > /var/www/webroot/chat/js/cache/compiled-${BUILD}.js

# install php dependencies
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ git
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl mbstring opcache zip

# install composer dependencies
USER www-data
RUN cd /var/www && php /tmp/composer.phar install --no-dev --no-ansi --no-interaction

USER root
# remove composer
RUN rm -rf /tmp/composer.phar /var/www/composer.*

# configure apache
COPY deploy/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN service apache2 restart

# start httpd
CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]

EXPOSE 80
EXPOSE 443
