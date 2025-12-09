#!/bin/sh

env > /etc/environment

php-fpm85
nginx
crond

node live-update.js &
tail -f /var/log/nginx/access.log &
tail -f /var/log/nginx/error.log &
tail -f /app/logs/*.log &
wait
