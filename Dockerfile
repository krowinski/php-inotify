FROM php:7.1-cli

RUN pecl install inotify \
    && docker-php-ext-enable inotify

VOLUME ["/data"]

