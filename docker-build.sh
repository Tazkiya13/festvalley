#!/bin/bash

# Build and run FestValley Laravel Application with Docker

echo "🚀 Building FestValley Docker Container..."

# Build the Docker image
docker build -t festvalley-app .

echo "✅ Docker image built successfully!"

echo "🔧 Starting the application..."

# Run with Docker Compose
docker-compose up -d

echo "🎉 FestValley is now running!"
echo ""
echo "📍 Access your application at:"
echo "   🌐 Web App: http://localhost:8000"
echo "   🔌 WebSocket: http://localhost:8080"
echo ""
echo "📋 Useful commands:"
echo "   docker-compose logs -f app     # View logs"
echo "   docker-compose down            # Stop application"
echo "   docker-compose restart app     # Restart application"
echo "   docker exec -it festvalley-app bash  # Access container shell"
echo ""
echo "🔍 Check status:"
docker-compose ps
