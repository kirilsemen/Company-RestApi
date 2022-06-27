# Lumen Test Task.

## 1. Set up Project.
1. At first, we need to run this command `composer install`.
2. Then to set up database with testes data, we need to run `php artisan migrate:fresh --seed`.
3. Then, we run this command `php -S localhost:8001 -t ./public`, you can select you own port,
and we can start testing application.
4. To set up `.env`, you can use my data from `.env.example`.

P.S. I was using local docker container with Postgresql.
