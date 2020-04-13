FROM httpd:2.4.33-alpine

RUN apk update; apk upgrade;

RUN cat /usr/local/apache2/conf/httpd.conf

COPY server/default.conf /usr/local/apache2/conf/httpd.conf
