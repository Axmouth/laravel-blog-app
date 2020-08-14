export COMPOSE_INTERACTIVE_NO_CLI=1
export UID
docker-compose build
docker-compose up -d --remove-orphans

#docker-compose exec laravel-blog-app-backend php ./artisan cache:clear
#docker-compose exec laravel-blog-app-backend php ./artisan route:clear
#docker-compose exec laravel-blog-app-backend php ./artisan config:clear
#docker-compose exec laravel-blog-app-backend php ./artisan view:clear
#docker-compose exec laravel-blog-app-backend php ./artisan optimize
#docker-compose exec laravel-blog-app-backend php ./artisan storage:link
docker-compose exec laravel-blog-app-backend php ./artisan migrate
