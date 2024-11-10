# Use the latest official Nginx image as a base
FROM nginx:latest

# Install git and clean up to reduce image size
RUN apt-get update && apt-get install -y git && apt-get clean && rm -rf /var/lib/apt/lists/*

# Remove the default Nginx files (if you want to copy your own files afterward)
RUN rm -rf /usr/share/nginx/html/*

# Copy the current directory contents into the container at /usr/share/nginx/html
COPY . /usr/share/nginx/html

# Set permissions for the Nginx HTML directory
RUN chmod -R 755 /usr/share/nginx/html/

# Expose port 8000 for the Nginx server
EXPOSE 8000

# Start the NGINX server
CMD ["nginx", "-g", "daemon off;"]
