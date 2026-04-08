#!/bin/bash
set -e

ENV_FILE="/var/www/.env"

log() {
    echo "[entrypoint] $1"
}

# Load env vars from .env file if they aren't already set
if [ -f "${ENV_FILE}" ]; then
    for VAR in XDEBUG PHP_IDE_CONFIG REMOTE_HOST; do
        if [ -z "${!VAR}" ]; then
            VALUE=$(grep -E "^${VAR}=" "${ENV_FILE}" | cut -d '=' -f 2- | tr -d '"' | tr -d "'")
            if [ -n "${VALUE}" ]; then
                export "$VAR=$VALUE"
                log "Loaded $VAR from .env"
            fi
        fi
    done
fi

# Default REMOTE_HOST
if [ -z "${REMOTE_HOST}" ]; then
    REMOTE_HOST="host.docker.internal"
    export REMOTE_HOST
    log "REMOTE_HOST defaulted to host.docker.internal"
fi

# Start cron service
log "Starting cron service..."
service cron start

# XDebug toggle
XDEBUG_INI="/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"

if [ "${XDEBUG}" = "true" ]; then
    log "Enabling XDebug..."
    if [ ! -f "${XDEBUG_INI}" ]; then
        # Remove & re-add PHP_IDE_CONFIG to cron to avoid duplication
        sed -i '/PHP_IDE_CONFIG/d' /etc/cron.d/laravel-scheduler
        if [ -n "${PHP_IDE_CONFIG}" ]; then
            echo -e "PHP_IDE_CONFIG=\"${PHP_IDE_CONFIG}\"\n$(cat /etc/cron.d/laravel-scheduler)" \
                > /etc/cron.d/laravel-scheduler
        fi

        docker-php-ext-enable xdebug
        {
            echo "xdebug.mode=debug,develop"
            echo "xdebug.start_with_request=yes"
            echo "xdebug.client_host=${REMOTE_HOST}"
            echo "xdebug.client_port=9003"
            echo "xdebug.log=/var/log/xdebug.log"
            echo "xdebug.idekey=VSCODE"
        } > "${XDEBUG_INI}"
        log "XDebug enabled with host=${REMOTE_HOST}"
    fi
else
    if [ -f "${XDEBUG_INI}" ]; then
        log "Disabling XDebug..."
        sed -i '/PHP_IDE_CONFIG/d' /etc/cron.d/laravel-scheduler
        rm -f "${XDEBUG_INI}"
        log "XDebug disabled"
    fi
fi

# Wait for DB to be ready (if DB_HOST is set)
if [ -n "${DB_HOST}" ]; then
    log "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
    MAX_RETRIES=30
    COUNT=0
    until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
        COUNT=$((COUNT+1))
        if [ $COUNT -ge $MAX_RETRIES ]; then
            log "ERROR: Database not available after ${MAX_RETRIES} retries. Exiting."
            exit 1
        fi
        log "DB not ready, retry $COUNT/$MAX_RETRIES..."
        sleep 2
    done
    log "Database is ready!"
fi

log "Starting PHP-FPM..."
exec "$@"