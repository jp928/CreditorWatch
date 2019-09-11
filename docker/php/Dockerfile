FROM php:7.2-fpm-alpine
RUN apk add --no-cache \
        $PHPIZE_DEPS

RUN apk add --no-cache --update icu-libs icu icu-dev libxml2-dev libpng-dev git
RUN docker-php-ext-install gd intl mbstring simplexml
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN	apk update && \
	apk upgrade && \
	apk add --update curl openssl && \
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
	chmod +x /usr/local/bin/composer && \
	apk del curl openssl && \
	rm -rf /var/cache/apk/*

EXPOSE 9000
