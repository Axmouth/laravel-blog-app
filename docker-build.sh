export COMPOSE_INTERACTIVE_NO_CLI=1
export UID
docker-compose build
docker-compose up -d --remove-orphans

docker-compose exec -T laravel-blog-app-backend php ./artisan cache:clear
docker-compose exec -T laravel-blog-app-backend php ./artisan route:clear
docker-compose exec -T laravel-blog-app-backend php ./artisan config:clear
docker-compose exec -T laravel-blog-app-backend php ./artisan view:clear
docker-compose exec -T laravel-blog-app-backend php ./artisan optimize
docker-compose exec -T laravel-blog-app-backend rm -rf public/storage
docker-compose exec -T laravel-blog-app-backend php ./artisan storage:link
docker-compose exec -T laravel-blog-app-backend php ./artisan migrate --force

docker-compose exec -T laravel-blog-app-backend mkdir -p /var/www/storage/app/public/profile_images
docker-compose exec -T laravel-blog-app-backend mkdir -p /var/www/storage/app/public/cover_images
docker-compose exec -T laravel-blog-app-backend chown -R www-data: .
