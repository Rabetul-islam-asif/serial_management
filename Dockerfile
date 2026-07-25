FROM php:8.2-cli

# Install MySQL PDO extension required by PDO database driver
RUN docker-php-ext-install pdo pdo_mysql

# Copy application files
COPY . /var/www/html
WORKDIR /var/www/html

# Default port
EXPOSE 10000

# Run PHP built-in web server serving the /public folder
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
