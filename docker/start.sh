#!/bin/sh
# Runs every time the container starts on Render.
set -e
cd /var/www/html

# Listen on the port Render gives us
sed -i "s/__PORT__/${PORT:-10000}/" /etc/nginx/http.d/default.conf

# Fail early with a clear message if a required setting is missing
for var in APP_KEY DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD; do
    eval "val=\${$var}"
    if [ -z "$val" ]; then
        echo "Missing environment variable: $var (set it in the Render dashboard)" >&2
        exit 1
    fi
done

php artisan storage:link --force >/dev/null 2>&1 || true

# Cache settings, routes and views for speed (re-done on every deploy)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Bring the database up to date (safe to run on every start)
php artisan db:ensure-schema
php artisan migrate --force

chown -R www-data:www-data storage bootstrap/cache

exec /usr/bin/supervisord -c /etc/supervisord.conf
