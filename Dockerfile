FROM php:8.2

# Instalar dependências do sistema, incluindo PostgreSQL e outras dependências do PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar o conteúdo do diretório local para o contêiner
COPY . /var/www/html

# Instalar dependências do Laravel
RUN composer install

# Definir permissões para o diretório de armazenamento
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expor a porta 8000
EXPOSE 8000

# Comando para rodar a aplicação Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]