
docker build -f Dockerfile -t axmouth/laravel-blog-server .
#docker build -f server/apache/Dockerfile -t axmouth/laravel-blog-apache .
docker-compose up -d --remove-orphans
