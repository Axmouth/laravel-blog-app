export COMPOSE_INTERACTIVE_NO_CLI=1
docker-compose build
docker-compose up -d --remove-orphans
docker-compose exec -T laravel-blog-app php artisan migrate

