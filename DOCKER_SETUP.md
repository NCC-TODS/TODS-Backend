## Dockerized development

1. Copy env: `cp .env.dev .env`, then set `APP_KEY` and DB creds.
2. Build & start: `docker compose -f docker-compose.dev.yml up --build -d` (omit `-d` to watch logs).
3. URLs: app http://localhost:8080, Vite http://localhost:5173.
4. Migrate: `docker compose -f docker-compose.dev.yml run --rm app php artisan migrate --force`.
5. Queue worker: runs as `queue` service with the stack; stop/start independently if needed.
6. DB port: container 3306 → host 3307 by default. Adjust `ports` in `docker-compose.dev.yml` if you need another host port.

## Dockerized production (image-based)

1. Copy env: `cp .env.prod .env`, then set DB creds and URLs.
2. Generate `APP_KEY` before starting: `docker compose run --rm app php artisan key:generate --show` → put value into `.env`.
3. Build images: `docker compose build`.
4. Start stack: `docker compose up -d` (web mapped to host 8081 by default; change `ports` in `docker-compose.yml` if needed).
5. Bootstrap (after services are up):
   - `docker compose run --rm app php artisan migrate --force`
   - `docker compose run --rm app php artisan db:seed --force` (if seeding desired)
   - `docker compose run --rm app php artisan storage:link`
6. Nginx listens on container port 80; host port is whatever you map (default 8081).

## Notes
- Env templates mention Redis/Memcached/Mailpit hosts, but those services are not defined in compose. Add them if you need them, or point the env vars at external services.
- Nginx in prod uses `volumes_from: app` to share code/storage. If you prefer explicit mounts, replace it with a direct `storage:/var/www/html/storage` volume on the `web` service.

### Redis (optional)
- The image includes the `phpredis` extension. To use Redis set `CACHE_STORE=redis`, `QUEUE_CONNECTION=redis`, and `SESSION_DRIVER=redis` (optional) along with `REDIS_HOST/PORT/PASSWORD`, and ensure a Redis service is reachable.

## Secrets and keys
- Generate `APP_KEY` safely: `docker compose run --rm app php artisan key:generate --show` and place the output in `.env`.
- Generate a random base64 secret (if needed for other libs): `openssl rand -base64 32`.

