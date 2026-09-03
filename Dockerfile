FROM dunglas/frankenphp:php8.5-trixie AS app

RUN install-php-extensions intl pdo_mysql opcache

WORKDIR /app

ENV APP_ENV=prod \
    APP_DEBUG=0 \
    APP_SECRET=build-only-secret

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

COPY . .
RUN composer dump-autoload --no-dev --classmap-authoritative --no-interaction \
    && php bin/console cache:clear --no-warmup \
    && php bin/console assets:install \
    && php bin/console tailwind:build --minify --quiet --no-interaction \
    && php bin/console asset-map:compile

COPY docker/Caddyfile /etc/caddy/Caddyfile

EXPOSE 80
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
