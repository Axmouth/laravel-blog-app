FROM nginx

RUN rm -rf /etc/nginx/conf.d
COPY ./nginx/conf.d/ /etc/nginx/conf.d/
COPY ./nginx/php.conf /etc/nginx/php.conf

WORKDIR /var/www

# Copy existing application directory contents
COPY src .
COPY .env .

EXPOSE 80
STOPSIGNAL SIGTERM
CMD ["nginx", "-g", "daemon off;"]
