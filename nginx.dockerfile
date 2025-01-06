FROM nginx:stable-alpine

ENV NGINXUSER=laravel
ENV NGiNXGROUP=laravel

RUN /bin/mkdir -p "/var/www/html/public"

ADD nginx/default.conf /etc/nginx/conf.d/default.conf

RUN sed -i "s/user www-data/user ${NGINXUSER}/g" /etc/nginx/nginx.conf

RUN adduser -g ${NGiNXGROUP} -s /bin/sh -D ${NGiNXGROUP}
