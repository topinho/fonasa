FROM php:7.4-fpm

WORKDIR /var/www

# Instalamos dependencias
RUN apt-get update && apt-get install -y \
    build-essential \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    locales \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

RUN pecl install mcrypt-1.0.4 && docker-php-ext-enable mcrypt

RUN docker-php-ext-install gd

# Borramos cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalamos extensiones
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instalar composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# agregar usuario para la aplicación laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copiar el directorio existente a /var/www
COPY . /var/www

# copiar los permisos del directorio de la aplicación
COPY --chown=www:www . /var/www

# cambiar el usuario actual por www
USER www

# exponer el puerto 9000 e iniciar php-fpm server
EXPOSE 9000
CMD ["php-fpm"]