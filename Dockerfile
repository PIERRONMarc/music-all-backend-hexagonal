#syntax=docker/dockerfile:1.4

# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

FROM php:8.2-fpm-alpine AS app_php

WORKDIR /var/www/html

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer --link /usr/bin/install-php-extensions /usr/local/bin/

# persistent / runtime deps
RUN apk add --no-cache \
		git \
	;

RUN set -eux; \
    install-php-extensions \
    	intl \
    	zip \
    	apcu \
		opcache \
        xdebug \
    ;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer