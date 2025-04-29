FROM php:8.4-apache

# Install net-tools for arp
RUN apt-get update && apt-get install -y net-tools && rm -rf /var/lib/apt/lists/*

# Change Apache to listen on port 9998 and set ServerName
RUN sed -i "s/80/${HTTP_PORT:-9998}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf \
    && echo 'ServerName localhost' >> /etc/apache2/apache2.conf

EXPOSE ${HTTP_PORT:-9998}

# Start Apache in foreground
CMD ["apache2-foreground"]
