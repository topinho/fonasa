# Docker Inicio.
Autor: German Torres Serrano. (topinho180@gmail.com)
Version: 0.2.2 (2021-02-01)

# Descripción.
Configuración inicial de Docker para generar un nuevo ambiente de trabajo en laravel.

# Imagenes
1. 0.2.2 -> 7.4
2. nginx:alpine,
3. 0.2 -> mysql 8.0

# Inicio
1. Crear Imagenes. docker-compose up -d
3. Crear .env. docker-compose exec app cp .env.example .env
4. Generar key. docker-compose exec app php artisan key:generate
5. Borrar cache. docker-compose exec app php artisan config:cache
6. Instalar dependencias docker-compose exec app composer install

# Migrations, Models, Controllers
004. docker-compose exec app php artisan migrate
004. docker-compose exec app php db:seed

# Framework
Laravel 8
VueJs 2.10