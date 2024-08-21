# Link src/static to public/static
rm public/static
ln -s ../src/static public/static

# Install composer dependencies
composer install --no-dev --no-interaction

# Compile TailwindCSS
composer tailwindcss-compile

# Create template cache directory
mkdir -p template-cache
chmod 777 template-cache
