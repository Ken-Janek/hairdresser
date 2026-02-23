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

# Change Apache DocumentRoot to public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Create .env from example if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env || true; fi

EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
