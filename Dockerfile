FROM php:8.3-apache

# Disable conflicting MPM modules and enable required ones
RUN a2dismod mpm_event && a2enmod mpm_prefork rewrite headers

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Create .env file from example if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Expose port
EXPOSE 80

# Set Apache to listen on 0.0.0.0
RUN sed -i 's/Listen 80/Listen 0.0.0.0:80/' /etc/apache2/ports.conf

# Start Apache
CMD ["apache2-foreground"]
