#!/bin/bash

# Fest# Build and start containers using docker-compose
echo "🔧 Building Docker image..."
docker-compose -f docker-compose.existing-db.yml build --no-cache

echo "🐳 Starting containers with docker-compose..."
docker-compose -f docker-compose.existing-db.yml up -d

# Fix permissions and clear caches
echo "🔧 Fixing permissions and clearing caches..."
docker-compose -f docker-compose.existing-db.yml exec -T app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker-compose -f docker-compose.existing-db.yml exec -T app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
docker-compose -f docker-compose.existing-db.yml exec -T app chown www-data:www-data /var/www/html/database/database.sqlite
docker-compose -f docker-compose.existing-db.yml exec -T app chmod 664 /var/www/html/database/database.sqlite
docker-compose -f docker-compose.existing-db.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.existing-db.yml exec -T app php artisan cache:clear
docker-compose -f docker-compose.existing-db.yml exec -T app php artisan view:clear
docker-compose -f docker-compose.existing-db.yml exec -T app php artisan route:clear

# Restart containers to ensure all changes take effect
echo "🔄 Restarting containers..."
docker-compose -f docker-compose.existing-db.yml restart Docker Deployment Script with Existing Database
# This script deploys the application using docker-compose with existing database and storage

echo "🚀 Starting FestValley Docker Deployment with Existing Database..."

# Stop and remove existing containers using docker-compose
echo "📦 Stopping existing containers..."
docker-compose -f docker-compose.existing-db.yml down

echo "✅ Existing containers stopped and removed."

# Get current directory
CURRENT_DIR=$(pwd)

echo "📂 Using existing database: $CURRENT_DIR/database/database.sqlite"
echo "📂 Using existing storage: $CURRENT_DIR/storage"

# Ensure proper permissions for the database file and directory
echo "🔧 Setting database permissions..."
chown -R $USER:$USER database/
chmod 775 database/
chmod 664 database/database.sqlite

# Build and start containers using docker-compose
echo "� Building Docker image..."
docker-compose -f docker-compose.existing-db.yml build --no-cache

echo "🐳 Starting containers with docker-compose..."
docker-compose -f docker-compose.existing-db.yml up -d

# Wait for services to start
echo "⏳ Waiting for services to start..."
sleep 5

# Check if container is running using docker-compose
if [ "$(docker-compose -f docker-compose.existing-db.yml ps -q)" ]; then
    echo "✅ Container is running!"
    
    # Test the application
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:9901)
    if [ "$HTTP_CODE" = "200" ]; then
        echo "✅ Application is responding successfully!"
        echo ""
        echo "🎉 Deployment Complete!"
        echo "📱 Web Application: http://localhost:9901"
        echo "🔌 WebSocket (Reverb): http://localhost:0880 (internal / not exposed)"
        echo ""
        echo "📊 Container Status:"
        docker-compose -f docker-compose.existing-db.yml ps
        echo ""
        echo "📝 To view logs: docker-compose -f docker-compose.existing-db.yml logs -f"
        echo "🛑 To stop: docker-compose -f docker-compose.existing-db.yml down"
        
        # Verify existing database is being used
        USER_COUNT=$(docker exec festvalley-container sqlite3 /var/www/html/database/database.sqlite "SELECT COUNT(*) FROM users;" 2>/dev/null || echo "0")
        echo "💾 Database Status: $USER_COUNT users found (existing data preserved)"
    else
        echo "❌ Application not responding (HTTP $HTTP_CODE)"
        echo "📝 Check logs: docker-compose -f docker-compose.existing-db.yml logs"
    fi
else
    echo "❌ Container failed to start"
    echo "📝 Check logs: docker-compose -f docker-compose.existing-db.yml logs"
fi
