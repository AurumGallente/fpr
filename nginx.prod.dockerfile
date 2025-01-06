FROM nginx:stable-alpine

ENV NGINXUSER=laravel
ENV NGiNXGROUP=laravel

RUN /bin/mkdir -p "/var/www/html/public"

ADD nginx/default.prod.conf /etc/nginx/conf.d/default.conf
ADD laravel-docker.test.pem /etc/nginx/certs/laravel-docker.test.pem
ADD laravel-docker.test-key.pem /etc/nginx/certs/laravel-docker.test-key.pem

RUN sed -i "s/user www-data/user ${NGINXUSER}/g" /etc/nginx/nginx.conf

RUN adduser -g ${NGiNXGROUP} -s /bin/sh -D ${NGiNXGROUP}
