# Deployment Guide

Currently, your Docker image is **local only**. This means it lives on your computer and hasn't been uploaded to the "cloud" (like Docker Hub or Google Container Registry).

To run this on another machine, you have two options.

## Option 1: Transfer Source Code (Easiest)
This method involves copying your project files to the new server and letting Docker build the image there.

1. **Copy Files**: Zip up your project folder (excluding `node_modules` and `.git` is fine) and transfer it to the server.
   * *Key files needed:* `Dockerfile`, `docker-compose.yml`, `application/`, `assets/`, `system/`, `DATABASE_FILE/`.
2. **Install Docker**: Ensure the new machine has Docker and Docker Compose installed.
3. **Run**:
   ```bash
   cd /path/to/project
   docker-compose up -d --build
   ```
   *This will compile the PHP environment and set up the Database from scratch on that server.*

## Option 2: Use a Cloud Registry (Advanced)
This method allows you to "download" your specific app image on any server without copying the source code files.

1. **Create an Account**: Sign up at [hub.docker.com](https://hub.docker.com/).
2. **Login**:
   ```bash
   docker login
   ```
3. **Tag your Image**:
   *Replace `your-username` with your Docker Hub username.*
   ```bash
   docker tag inventory-app your-username/inventory-app:v1
   ```
4. **Push to Cloud**:
   ```bash
   docker push your-username/inventory-app:v1
   ```
5. **Run on Server**:
   On the other machine, you don't need the code, just this `docker-compose.yml` (simplified):
   ```yaml
   version: '3.8'
   services:
     web:
       image: your-username/inventory-app:v1
       ports:
         - "8080:80"
       environment:
         - DB_HOST=db
     db:
       image: mysql:8.0
       # ... rest of db config
   ```
