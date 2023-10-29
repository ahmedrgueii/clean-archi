ARG PHP_VERSION=8.1

FROM php:${PHP_VERSION}-fpm

# Certificates
COPY certs /usr/local/share/ca-certificates
COPY certs /usr/lib/ssl

# Dependencies
RUN  update-ca-certificates \
    && apt-get update && apt-get upgrade -y \
    && apt-get install -y \
    git \
    curl \
    zip \
    vim \
    wget \
    unzip \
    && docker-php-ext-install pdo_mysql

# Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash

# NodeJS, Npm and Yarn
RUN apt-get update && \
    apt-get install -y --no-install-recommends gnupg && \
    curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get update && \
    apt-get install -y --no-install-recommends nodejs && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && \
    apt-get install -y --no-install-recommends yarn \
    && yarn config set strict-ssl true -g

WORKDIR /var/www
