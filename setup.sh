#!/bin/bash

set -e

echo "Installing PHP dependencies..."
composer install

echo "Installing Node dependencies..."
npm install

if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
else
    echo ".env already exists. Skipping..."
fi

echo "Generating Laravel app key..."
php artisan key:generate

echo "Generating JWT secret..."
php artisan jwt:secret --force

echo "Running migrations and seeders..."
php artisan migrate:fresh --seed

echo ""
echo "Setup complete."
echo ""
echo "To run the app:"
echo "Terminal 1: php artisan serve"
echo "Terminal 2: npm run dev"
echo ""
echo "Open: http://127.0.0.1:8000"
echo ""
echo "Reviewer login:"
echo "Email: reviewer@example.com"
echo "Password: password123"