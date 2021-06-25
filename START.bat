docker-compose up -d --build
docker-compose exec php composer install
docker-compose exec php php bin/console doctrine:schema:create
pause