#!/bin/sh

env > /etc/environment

php-fpm83
nginx
crond

tail -f /var/log/nginx/access.log &
tail -f /var/log/nginx/error.log &
wait
