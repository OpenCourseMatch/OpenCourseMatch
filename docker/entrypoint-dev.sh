#!/bin/sh

env > /etc/environment

php-fpm83
nginx
crond

cd /app && composer build

tail -f /var/log/nginx/access.log
