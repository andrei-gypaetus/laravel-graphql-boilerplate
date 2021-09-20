FROM php:7.4-fpm-alpine

RUN apk add --no-cache --virtual .build-deps freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
		&& docker-php-ext-configure gd \
				--with-freetype=/usr/include/ \
				--with-jpeg=/usr/include/ \
		&&	NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
		&&  docker-php-ext-install -j${NPROC} gd \
		&&	apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        oniguruma-dev \
        curl-dev \
        imagemagick-dev \
        libtool \
        libxml2-dev \
        postgresql-dev \
        sqlite-dev \
    && apk add --no-cache \
        curl \
        git \
        imagemagick \
        mysql-client \
        postgresql-libs \
        libintl \
        icu \
        icu-dev \
        libzip-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install \
        bcmath \
        curl \
        iconv \
        mbstring \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        pcntl \
        tokenizer \
        xml \
        zip \
        intl \
        sockets \
    && curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
    && apk del -f .build-deps


RUN apk --update add gcc make g++ zlib-dev

RUN apk update \
    && apk add autoconf

RUN pecl install xdebug-2.9.0 \
  && docker-php-ext-enable xdebug

COPY . /var/www

WORKDIR /var/www

COPY scripts/start.sh /start.sh

EXPOSE 9000

RUN chmod +x /var/www/scripts/start.sh

ENTRYPOINT ["sh", "/var/www/scripts/start.sh"]
