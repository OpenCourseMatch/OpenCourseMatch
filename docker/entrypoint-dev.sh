#!/bin/sh

env > /etc/environment

php-fpm83
nginx
crond

npx tailwindcss --input src/static/css/base.css --output src/static/css/style.css --watch=always --poll &
node live-update.js &
tail -f /var/log/nginx/access.log &
wait
