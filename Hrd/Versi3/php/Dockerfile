FROM php:8.3-fpm

ENV PS1="\u@\h:\w\\$ "
ENV TZ="Asia/Jakarta"
ENV COMPOSER_MEMORY_LIMIT='-1'

# Set timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install system dependencies in a single layer to reduce image size
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libmemcached-dev \
        libmcrypt-dev \
        libreadline-dev \
        libgmp-dev \
        libzip-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        libmagickwand-dev \
        libxml2-dev \
        git \
        cron \
        nano \
        curl \
        unzip \
        nodejs \
        npm && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions (grouped to reduce layers)
RUN docker-php-ext-install \
        soap \
        exif \
        pcntl \
        intl \
        gmp \
        zip \
        pdo_mysql \
        pdo_pgsql \
        bcmath \
        opcache

# Configure and install GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Install PECL extensions
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl install imagick && docker-php-ext-enable imagick
RUN pecl install memcached && docker-php-ext-enable memcached

# Install xDebug (only install, enable conditionally via entrypoint)
RUN pecl install xdebug

# OPcache configuration for production performance
RUN { \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/local/bin/composer
RUN echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc

# Laravel Schedule Cron Job
RUN echo "* * * * * www-data /usr/local/bin/php /var/www/artisan schedule:run >> /dev/null 2>&1" \
    > /etc/cron.d/laravel-scheduler && \
    chmod 0644 /etc/cron.d/laravel-scheduler

# Add PHP ini config
ADD ./local.ini /usr/local/etc/php/conf.d/local.ini

# CLI Aliases
RUN printf '#!/bin/bash\n/usr/local/bin/php /var/www/vendor/bin/dep "$@"\n' > /usr/bin/dep && chmod +x /usr/bin/dep
RUN printf '#!/bin/bash\n/usr/local/bin/php /var/www/artisan "$@"\n' > /usr/bin/art && chmod +x /usr/bin/art
RUN printf '#!/bin/bash\n/usr/local/bin/php /var/www/artisan migrate "$@"\n' > /usr/bin/migrate && chmod +x /usr/bin/migrate
RUN printf '#!/bin/bash\n/usr/local/bin/php /var/www/artisan migrate:fresh --seed\n' > /usr/bin/fresh && chmod +x /usr/bin/fresh
RUN printf '#!/bin/bash\n/usr/local/bin/php /var/www/artisan config:clear\n/var/www/vendor/bin/phpunit -d memory_limit=2G --stop-on-error --stop-on-failure --testdox-text=tests/report.txt "$@"\n' > /usr/bin/t && chmod +x /usr/bin/t

WORKDIR /var/www

COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
EXPOSE 9000
CMD ["php-fpm"]