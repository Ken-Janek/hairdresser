FROM php:8.3-cli

WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy application files
COPY . .

# Remove .env files - use Railway environment variables instead
RUN rm -f .env .env.example .env.production

EXPOSE 8080

# Start PHP built-in server
CMD sh -c 'php -S 0.0.0.0:${PORT:-8080} -t public/'
