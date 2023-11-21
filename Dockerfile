# Use NGINX as the base image
FROM nginx:latest

# Copy your HTML content into the NGINX default directory
COPY html /var/www/html

# Expose port 80 to make the NGINX server accessible
EXPOSE 80