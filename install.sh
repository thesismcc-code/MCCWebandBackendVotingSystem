#!/usr/bin/env bash

set -euo pipefail

echo "Starting EVMS dependency installation..."

if ! command -v composer >/dev/null 2>&1; then
    echo "Error: Composer is not installed or not in PATH."
    exit 1
fi

if ! command -v npm >/dev/null 2>&1; then
    echo "Error: npm is not installed or not in PATH."
    exit 1
fi

if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        echo "Creating .env from .env.example..."
        cp .env.example .env
    else
        echo "Warning: .env.example not found. Skipping .env creation."
    fi
fi

echo "Installing PHP dependencies..."
composer install

echo "Installing JavaScript dependencies..."
npm install

echo "Building frontend assets..."
npm run build

if [ -f ".env" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

echo "Running migrations..."
php artisan migrate --force

echo "Installation complete."
echo "Next step: start the app with 'php artisan serve'"
