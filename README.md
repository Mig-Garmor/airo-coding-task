# AIRO Coding Challenge

## Requirements

Before running the project, make sure you have the following installed:

- PHP
- Composer
- Node.js / npm
- MySQL

## Quick setup

First, make sure MySQL is running.

Create the local database:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE airo_coding_task;
```

Copy the environment file:

```bash
cp .env.example .env
```

Update the database credentials in `.env` if needed:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=airo_coding_task
DB_USERNAME=root
DB_PASSWORD=
```

Then run the setup script:

```bash
chmod +x setup.sh
./setup.sh
```

The setup script will:

- Install PHP dependencies
- Install Node dependencies
- Generate the Laravel app key
- Generate the JWT secret
- Run migrations
- Seed the reviewer user

## Run the app

Start Laravel:

```bash
php artisan serve
```

In a second terminal, start Vite:

```bash
npm run dev
```

Open:

```text
http://127.0.0.1:8000
```

## Reviewer login

```text
Email: reviewer@example.com
Password: password123
```

## Run tests

```bash
php artisan test
```

## Manual setup

If you prefer not to use `setup.sh`, run the steps manually:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret --force
php artisan migrate:fresh --seed
```

Then start Laravel and Vite:

```bash
php artisan serve
npm run dev
```

## Main endpoint

```text
POST /quotation
```

The route is intentionally not prefixed with `/api` because the challenge specifies `/quotation`.
