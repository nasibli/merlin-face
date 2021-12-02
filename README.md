# merlin-face

## Project setup
```
docker-compose build
docker-compose up -d

docker exec merlin-face-php-cli composer install
docker exec merlin-face-php-cli mkdir db
docker exec merlin-face-php-cli php bin/console doctrine:migrations:migrate
docker exec merlin-face-php-cli chmod -R 777 /var/www/merlin-face/db

docker-compose down
```

### Project run
```
docker-compose up -d
docker exec merlin-face-php-cli php bin/console messenger:consume async
```

### Project stop
```
docker-compose down
```
