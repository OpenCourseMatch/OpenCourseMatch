# Builder
FROM alpine:latest AS builder

# Update package manager
RUN apk update && apk upgrade
RUN apk --no-cache add tzdata

# Install PHP, composer, nodejs and npm
RUN apk --no-cache add php83 php83-fpm composer nodejs npm git

# Install PHP packages
RUN apk --no-cache add php-session php-tokenizer php-mysqli php-pdo php-pdo_mysql php-curl php-gd php-intl php-mbstring php-xml php-simplexml php-dom php-ctype php-apcu

# Set working directory
WORKDIR /app

# Copy application files
COPY --chown=nginx:nginx . .
COPY ./docker/nodejs .

# Link src/static to public/static
RUN rm -rf /app/public/static && ln -s /app/src/static /app/public/static

# Install composer dependencies
RUN composer install --no-dev --no-interaction

# Install npm dependencies
RUN npm install

# Build tailwindcss
RUN npx tailwindcss --input src/static/css/base.css --output src/static/css/style.css --minify



# Runner
FROM alpine:latest AS runner

# Update package manager
RUN apk update && apk upgrade
RUN apk --no-cache add tzdata

# Install nginx and PHP
RUN apk --no-cache add nginx php83 php83-fpm git

# Install PHP packages
RUN apk --no-cache add php-session php-tokenizer php-mysqli php-pdo php-pdo_mysql php-curl php-gd php-intl php-mbstring php-xml php-simplexml php-dom php-ctype php-apcu

# Set working directory
WORKDIR /app

# Copy application files
RUN mkdir -p framework && \
    mkdir -p public && \
    mkdir -p src && \
    mkdir -p vendor

COPY --from=builder /app/framework ./framework
COPY --from=builder /app/public ./public
COPY --from=builder /app/src ./src
COPY --from=builder /app/vendor ./vendor
COPY ./docker/entrypoint.sh .

# Copy server configurations
COPY ./docker/nginx-config /etc/nginx
COPY ./docker/php-fpm-config /etc/php83/php-fpm.d

# Create remaining directories and adjust permissions
RUN mkdir -p logs && \
    mkdir -p files && \
    mkdir -p template-cache && \
    chown -R nginx:nginx logs && \
    chown -R nginx:nginx files && \
    chown -R nginx:nginx template-cache && \
    chmod 777 logs && \
    chmod 777 files && \
    chmod 777 template-cache && \
    chmod +x entrypoint.sh

# Setup crontab
RUN crontab -u nginx src/cronjobs/.crontab

EXPOSE 80
ENTRYPOINT ["/app/entrypoint.sh"]
