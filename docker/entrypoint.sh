#!/bin/sh
set -e

# Ensure socket directory exists and has correct permissions
mkdir -p /var/run
chmod 777 /var/run

# Remove old socket if exists
rm -f /var/run/php-fpm.sock

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

