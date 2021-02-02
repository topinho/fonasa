# Docker Inicio.
Autor: German Torres Serrano. (topinho180@gmail.com)
Version: 0.2.2 (2021-02-01)

# Descripción.
Configuración inicial de Docker para generar un nuevo ambiente de trabajo para laravel.

# Imagenes
1. PHP 7.2 / 0.2 -> 7.3 / 0.2.2 -> 7.4
2. nginx:alpine,
3. mysql:5.7.22 / 0.2 -> mysql 8.0

# Descarga.

1. Editar nombre de container, base de datos y usuario de base de datos en docker-compose.
2. Cambiar puerto de webserver 300XX por el disponible.
3. Cambiar puerto de db 336XX por el disponible.

# Inicio
1. Crear Imagenes. docker-compose up -d
2. Crear Proyecto docker-compose exec app composer create-project --prefer-dist laravel/laravel
3. Crear .env. docker-compose exec app cp .env.example .env
3. Editar .env, agregar nombre, usuario y clave de base de datos y DB_HOST=db
4. Generar key. docker-compose exec app php artisan key:generate
5. Borrar cache. docker-compose exec app php artisan config:cache

# Sanctum
1. Install sanctum.
    docker-compose exec app composer require laravel/sanctum
2. Publicar archivo de configuracion.
    docker-compose exec app php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" 
3. Run migrations. 
    docker-compose exec app php artisan migrate
4. Agregar a Kernel.php en protected $middlewareGroups 'api'
    EnsureFrontendRequestsAreStateful::class, 
5. Agregar variable de entorno en .env
    SANCTUM_STATEFUL_DOMAINS=localhost
6. Edit User.php para declarar y usar HasApiToken.
7. Agregar en path config/cors.php
    '/sanctum/csrf-cookie'

# Migrations, Models, Controllers
001. docker-compose exec app php artisan make:migration create_hospitales_table 
001. docker-compose exec app php artisan migrate
