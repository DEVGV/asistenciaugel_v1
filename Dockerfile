FROM php:8.3-cli

# Instalar dependencias del sistema y librerías necesarias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Instalar Node.js (versión 20) para compilar el frontend con Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Limpiar caché de apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP (incluye pdo_pgsql para PostgreSQL)
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto de la aplicación (8000) y de Vite (5190)
EXPOSE 8000
EXPOSE 5190

# En entornos de desarrollo, ejecutamos las instalaciones y levantamos los servicios
CMD composer install && npm install && (php artisan migrate || true) && composer dev
