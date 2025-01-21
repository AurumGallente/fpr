FROM php:8-fpm-alpine

ENV PHPUSER=laravel
ENV PHPGROUP=laravel
ENV PATH="/opt/venv/bin:$PATH"
ENV PYTHONUNBUFFERED=1


# Update package list and install dependencies
RUN apk update && apk add  \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    fcgiwrap \
    libmcrypt-dev \
    libpq-dev \
    oniguruma-dev \
    && rm -rf /var/lib/apt/lists/*

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf

RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN /bin/mkdir -p "/var/www/html/public"

RUN docker-php-ext-install pdo pdo_pgsql -j$(nproc) mbstring zip exif pcntl bcmath opcache

RUN apk add --update --no-cache python3 && ln -sf python3 /usr/bin/python

RUN apk add --update py-pip

RUN apk add --update linux-headers

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}

RUN chown -R www-data:www-data /var/www/html/

RUN python -m venv /opt/venv

RUN pip3 install py-readability-metrics

RUN pip3 install pycountry

RUN python -m nltk.downloader -d /usr/local/share/nltk_data punkt_tab

RUN python -m nltk.downloader -d /usr/local/share/nltk_data stopwords


CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
