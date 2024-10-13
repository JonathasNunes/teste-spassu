# ./backend/Dockerfile

# Use a imagem oficial do PHP com FPM
FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala as extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# O Xdebug é uma extensão que fornece funcionalidades de depuração e geração de relatórios de cobertura de código.
# RUN if ! php -m | grep -q 'xdebug'; then \
#         pecl install xdebug && \
#         docker-php-ext-enable xdebug; \
#     fi

# # Ativar Xdebug (presumindo que já está instalado)
# RUN docker-php-ext-enable xdebug

# # Copia as configurações do Xdebug
# COPY ./docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/

# Copia o arquivo de configuração do PHP (opcional)
# COPY ./php.ini /usr/local/etc/php/

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o código fonte da aplicação para o contêiner
COPY . .

# Definindo as permissões corretas (opcional)
RUN chown -R www-data:www-data /var/www/html

# Instalar dependências do PHP
RUN composer install

# Expor a porta (opcional)
# Expondo a porta 9000, que é a padrão para o PHP-FPM
EXPOSE 9000

# Inicia o servidor PHP-FPM
CMD ["php-fpm"]


