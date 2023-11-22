#!/bin/sh
set -eu

echo "Setting permissions on cache volume"
chown -R www-data:www-data /src/bf2sclone/cache
find /src/bf2sclone/cache -type d -exec chmod 750 {} \;
find /src/bf2sclone/cache -type f -exec chmod 640 {} \;

exec /usr/bin/supervisord -c /supervisor.conf --pidfile /run/supervisord.pid
