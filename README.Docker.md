# Docker Setup for TODS Backend

This project includes Docker configuration for both development and production environments.

## Files Structure

- `Dockerfile` - Multi-stage build for optimized production image
- `docker-compose.yml` - Development environment configuration
- `docker-compose.prod.yml` - Production environment configuration
- `env.development.example` - Example development environment variables
- `env.production.example` - Example production environment variables
- `docker/nginx.conf` - Nginx web server configuration
- `docker/supervisord.conf` - Supervisor configuration for managing services
- `docker/php/local.ini` - PHP configuration for development

## Quick Start

### Development Mode

1. Copy the example environment file:
   ```bash
   cp env.development.example .env.development
   ```

2. Update `.env.development` with your settings (especially database passwords)

3. Build and start containers:
   ```bash
   docker-compose up -d --build
   ```

4. Generate application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

5. Run migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. Access the application at `http://localhost:8000`

### Production Mode

1. Copy the example environment file:
   ```bash
   cp env.production.example .env.production
   ```

2. Update `.env.production` with your production settings:
   - Change `APP_URL` to your domain
   - Set strong passwords for `DB_PASSWORD` and `DB_ROOT_PASSWORD`
   - Generate `APP_KEY` using `php artisan key:generate`
   - Update `SANCTUM_STATEFUL_DOMAINS` with your domain
   - Configure mail settings

3. Build and start containers:
   ```bash
   docker-compose -f docker-compose.prod.yml --env-file .env.production up -d --build
   ```

4. Run migrations:
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
   ```

5. Optimize for production:
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan optimize
   ```

6. Access the application at `http://your-server-ip` (port 80) or configure SSL for port 443

## Database Access

### Development
- Database is accessible on `localhost:3306` for local development tools
- Connection: `mysql -h 127.0.0.1 -P 3306 -u tods_user -p`

### Production
- Database is **NOT** exposed to the host machine (only accessible within Docker network)
- To access, use: `docker-compose -f docker-compose.prod.yml exec database mysql -u tods_user -p`

## SSL/HTTPS Configuration

To enable HTTPS on port 443:

1. Place your SSL certificates in `docker/ssl/` directory:
   - `cert.pem` - SSL certificate
   - `key.pem` - Private key

2. Uncomment the SSL server block in `docker/nginx.conf`

3. Update the `server_name` directive with your domain

4. Rebuild the container:
   ```bash
   docker-compose -f docker-compose.prod.yml up -d --build
   ```

## Performance Optimizations

The Docker setup includes several performance optimizations:

- **Multi-stage build** - Reduces final image size
- **Alpine Linux** - Lightweight base images
- **OPcache enabled** - PHP bytecode caching
- **Nginx + PHP-FPM** - High-performance web server stack
- **MariaDB optimizations** - Tuned for production workloads
- **Redis caching** - Fast in-memory caching
- **Gzip compression** - Reduced bandwidth usage
- **Static file caching** - Optimized asset delivery

## Useful Commands

### View logs
```bash
# Development
docker-compose logs -f app

# Production
docker-compose -f docker-compose.prod.yml logs -f app
```

### Execute Artisan commands
```bash
# Development
docker-compose exec app php artisan <command>

# Production
docker-compose -f docker-compose.prod.yml exec app php artisan <command>
```

### Access container shell
```bash
# Development
docker-compose exec app sh

# Production
docker-compose -f docker-compose.prod.yml exec app sh
```

### Stop containers
```bash
# Development
docker-compose down

# Production
docker-compose -f docker-compose.prod.yml down
```

### Remove volumes (⚠️ deletes data)
```bash
# Development
docker-compose down -v

# Production
docker-compose -f docker-compose.prod.yml down -v
```

## Security Notes

- **Production database** is only accessible within the Docker network
- **Redis** in production is only accessible within the Docker network
- Always use strong passwords in production `.env.production`
- Keep your `.env.production` file secure and never commit it to version control
- Regularly update base images for security patches

