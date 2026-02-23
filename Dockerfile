FROM php:8.3-apache

WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Disable event MPM to avoid conflicts
RUN a2dismod mpm_event 2>/dev/null || true

# Enable rewrite module
RUN a2enmod rewrite

# Copy application files
COPY . .

# Remove .env files - use Railway environment variables instead
RUN rm -f .env .env.example .env.production

# Copy custom Apache configuration
COPY docker_apache.conf /etc/apache2/sites-available/000-default.conf

# Enable rewrite module (already done, but ensure it's enabled)
RUN a2enmod rewrite

EXPOSE 80



# Start Apache
CMD ["apache2-foreground"]
