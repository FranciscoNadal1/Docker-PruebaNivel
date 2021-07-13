docker-compose up -d --build
docker-compose exec php composer install
docker-compose exec php php bin/console doctrine:schema:create

mkdir ./FTPFolder/IN
mkdir ./FTPFolder/OUT
mkdir ./FTPFolder/ERROR
pause