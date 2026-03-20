FROM php:8.2-apache

# Instala as bibliotecas do sistema e o driver do PostgreSQL para o PHP
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copia os seus arquivos para a pasta do servidor
COPY . /var/www/html/

# Dá as permissões corretas
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
