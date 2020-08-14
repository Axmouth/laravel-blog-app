FROM nginx

RUN rm -rf /etc/nginx/conf.d
COPY ./nginx/conf.d/ /etc/nginx/conf.d/
COPY ./nginx/php.conf /etc/nginx/php.conf


# Add user for laravel application
#RUN groupadd -g 1000 www
#RUN useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www
# Copy existing application directory contents
COPY src .

# Or do this
#RUN chown -R www-data:www-data \
#    /var/www/public \
#    /var/www/storage \
#    /var/www/bootstrap/cache

# RUN mv .env.prod .env
COPY .env .




EXPOSE 80
STOPSIGNAL SIGTERM
CMD ["nginx", "-g", "daemon off;"]
