export COMPOSE_INTERACTIVE_NO_CLI=1
export UID
docker-compose build
docker-compose up -d --remove-orphans

docker-compose exec -T laravel-blog-app-backend php ./artisan migrate
