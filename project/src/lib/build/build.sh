# Install composer dependencies
composer install --no-dev --no-interaction

# Compile TailwindCSS
composer tailwindcss-compile

# Create template cache directory
mkdir -p project/template-cache
chmod 777 project/template-cache
