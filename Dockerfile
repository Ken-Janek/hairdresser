FROM php:8.3-cli

WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy application files
COPY . .

# Remove .env files - use Railway environment variables instead
RUN rm -f .env .env.example .env.production

# Copy and make entrypoint executable
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

# Use entrypoint script to handle PORT env variable
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
