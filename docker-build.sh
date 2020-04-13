
docker build -f Dockerfile -t axmouth/laravel-blog-server .
#docker build -f server/apache/Dockerfile -t axmouth/laravel-blog-apache .
docker-compose up -d --remove-orphans
docker-compose exec laravel-blog-app php artisan migrate
