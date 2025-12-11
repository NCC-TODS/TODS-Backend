#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

if ! grep -Eq '^APP_KEY=.{10,}$' .env.prod; then
  echo "APP_KEY not set in .env.prod. Generate and set it before running prod stack." >&2
  exit 1
fi

COMPOSE="docker compose"

echo "Building images..."
$COMPOSE build

echo "Starting stack..."
$COMPOSE up -d

echo "Running migrations..."
$COMPOSE run --rm app php artisan migrate --force

echo "Running seeds (optional)..."
$COMPOSE run --rm app php artisan db:seed --force || true

echo "Linking storage..."
$COMPOSE run --rm app php artisan storage:link

echo "Prod stack ready on host port 8081 (default)."

