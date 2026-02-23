FROM php:8.3-apache

WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Disable event MPM, enable rewrite
RUN a2dismod mpm_event && a2enmod rewrite

# Copy application files
COPY . .

# Copy Apache VirtualHost config
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Create .env from example if needed
RUN if [ ! -f .env ]; then cp .env.example .env || true; fi

EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
