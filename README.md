# AIRO Coding Challenge

## Setup

Install dependencies:

```bash
composer install
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the app key and JWT secret:

```bash
php artisan key:generate
php artisan jwt:secret
```

Create a MySQL database:

```sql
CREATE DATABASE airo_coding_task;
```

Update `.env` with your local database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=airo_coding_task
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seed the reviewer user:

```bash
php artisan migrate:fresh --seed
```

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

## Main endpoint

```text
POST /quotation
```

The route is intentionally not prefixed with `/api` because the challenge specifies `/quotation`.
