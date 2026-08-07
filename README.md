# E-Commerce System

A full-stack e-commerce application with a Laravel REST API, Vue.js frontend, Swagger documentation, and Docker-based local development.

## Stack

- Backend: PHP 8.3, Laravel 13, Laravel Sanctum, MySQL
- Frontend: Vue 3, Vue Router, Pinia, Axios, Bootstrap, Vite
- Infrastructure: Docker Compose, Apache, MySQL 8.4
- API Docs: OpenAPI 3.0 + Swagger UI

## Features

- User registration, login, logout, and profile
- Sanctum API token authentication
- Product and category management
- Product search, filtering, and pagination
- Shopping cart
- Checkout and order creation
- Order status management
- Payment records
- Admin inventory screen
- Swagger API documentation

## Project Structure

```text
.
├── backend/             Laravel API
├── frontend/            Vue application
├── docker-compose.yml   Local Docker stack
├── DOCKER.md            Docker notes
└── E-Commerce System.md Original project specification
```

## Run With Docker

From the project root:

```bash
docker compose up --build
```

Then open:

```text
Frontend:    http://127.0.0.1:5173
Backend API: http://127.0.0.1:8080/api
Swagger:     http://127.0.0.1:8080/docs
OpenAPI:     http://127.0.0.1:8080/openapi.yaml
```

MySQL is exposed on the host at:

```text
127.0.0.1:3308
```

Docker database credentials:

```env
DB_DATABASE=ecommerce
DB_USERNAME=ecommerce
DB_PASSWORD=secret
DB_HOST=mysql
```

## Seeded Users

```text
Admin:
admin@example.com / password

Customer:
customer@example.com / password
```

## Useful Commands

Stop containers:

```bash
docker compose down
```

Stop containers and remove the database volume:

```bash
docker compose down -v
```

View backend logs:

```bash
docker compose logs -f webserver
```

View frontend logs:

```bash
docker compose logs -f frontend
```

Run Laravel commands:

```bash
docker compose exec webserver php artisan route:list
```

## Local Development Without Docker

Backend:

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Frontend:

```bash
cd frontend
npm install
npm run dev
```

Set the frontend API URL in `frontend/.env`:

```env
VITE_API_URL=http://127.0.0.1:8000/api
```

## API Documentation

Swagger UI is available at:

```text
http://127.0.0.1:8080/docs
```

The OpenAPI specification is available at:

```text
http://127.0.0.1:8080/openapi.yaml
```
