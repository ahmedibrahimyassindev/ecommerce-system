# Docker Usage

Start the full stack:

```bash
docker compose up --build
```

Services:

- Backend API: http://127.0.0.1:8080/api
- Swagger UI: http://127.0.0.1:8080/docs
- Frontend: http://127.0.0.1:5173
- MySQL: 127.0.0.1:3308

Database credentials used by Docker Compose:

```env
DB_DATABASE=ecommerce
DB_USERNAME=ecommerce
DB_PASSWORD=secret
DB_HOST=mysql
```

The webserver container runs Apache with Laravel's public directory as the document root.
On startup it runs:

```bash
composer install
php artisan key:generate --force
php artisan migrate --seed --force
```

The frontend container runs:

```bash
npm install
npm run dev -- --host 0.0.0.0
```

Stop containers:

```bash
docker compose down
```

Remove containers and database volume:

```bash
docker compose down -v
```
