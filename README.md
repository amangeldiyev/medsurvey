## Installing process

1. Copy .env.example to .env

2. Configure .env

3. Run commands: 

```
docker-compose up -d --build
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan db:seed --class=SurveySeeder
docker-compose exec php php artisan voyager:install
docker-compose exec php php artisan voyager:admin admin@test.com

```
