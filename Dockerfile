FROM alpine
RUN apk add --no-cache -X http://dl-cdn.alpinelinux.org/alpine/edge/testing \
    php8-cli \
    php8-dom \
    php8-json \
    php8-mbstring \
    php8-xml \
    php8-pecl-inotify \
    php8-phar \
    php8-openssl \
    php8-curl \
    bash \
    inotify-tools

COPY composer.json .

RUN ln -s /usr/bin/php8 /usr/bin/php

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN composer install --verbose --ignore-platform-reqs --prefer-dist --no-progress --no-interaction --optimize-autoloader

WORKDIR /app
COPY . .