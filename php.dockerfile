FROM php:8-fpm-alpine

ENV PHPUSER=laravel
ENV PHPGROUP=laravel
ENV PATH="/opt/venv/bin:$PATH"
ENV PYTHONUNBUFFERED=1

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf

RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN /bin/mkdir -p "/var/www/html/public"

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --update --no-cache python3 && ln -sf python3 /usr/bin/python

RUN apk add --update py-pip

RUN python -m venv /opt/venv

RUN pip3 install py-readability-metrics

RUN python -m nltk.downloader -d /usr/local/share/nltk_data punkt_tab


CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
