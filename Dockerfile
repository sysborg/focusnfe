FROM php:8.4-cli

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install zip pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Cria uma nova aplicação Laravel
RUN composer create-project laravel/laravel testapp --no-interaction --prefer-dist

WORKDIR /app/testapp

# Copia o pacote para dentro do Laravel
COPY . ./packages/focusnfe

# Adiciona o pacote como repositório local no composer.json
RUN composer config repositories.focusnfe path ./packages/focusnfe

# Instala o pacote
RUN composer require sysborg/focusnfe:@dev --no-interaction

# Copia o phpunit.xml do pacote
COPY phpunit.xml ./phpunit.xml

# Configura o .env para testes
RUN cp .env.example .env && \
    php artisan key:generate && \
    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env && \
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=:memory:/' .env

# Publica as configurações do pacote se houver
RUN php artisan vendor:publish --provider="Sysborg\\FocusNFe\\app\\Providers\\SBFocusNFeProvider" --force || true

# Configura variáveis de ambiente para testes
ENV APP_ENV=testing
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=:memory:

# Comando padrão: rodar os testes com artisan
CMD ["php", "artisan", "test", "--testdox"]
