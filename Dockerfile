FROM php:8.3-apache

WORKDIR /var/www/html

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable required Apache modules
RUN a2enmod rewrite

# Copy application files
COPY . .

# Copy Apache config
COPY apache.conf /etc/apache2/sites-enabled/000-default.conf

# Create .env from example if needed
RUN if [ ! -f .env ]; then cp .env.example .env || true; fi

EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
