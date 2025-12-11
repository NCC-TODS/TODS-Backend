#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

if ! grep -Eq '^APP_KEY=.{10,}$' .env.dev; then
  echo "APP_KEY not set in .env.dev. Set it before running dev stack." >&2
  exit 1
fi

COMPOSE="docker compose -f docker-compose.dev.yml"

echo "Building and starting dev stack..."
$COMPOSE up --build -d

echo "Running migrations..."
$COMPOSE run --rm app php artisan migrate --force

echo "Dev stack ready:"
echo "- App:       http://localhost:8080"
echo "- Vite:      http://localhost:5173"
echo "- DB (host): 127.0.0.1:3307"

