# 🐳 FestValley Docker Deployment Guide

This guide provides Docker containerization for the FestValley Laravel application with SQLite database.

## 📋 Prerequisites

- Docker (version 20.10+)
- Docker Compose (version 2.0+)
- Git

## 🚀 Quick Start

### Option 1: Using Build Script (Recommended)
```bash
# Make script executable and run
chmod +x docker-build.sh
./docker-build.sh
```

### Option 2: Manual Commands
```bash
# Build the Docker image
docker build -t festvalley-app .

# Start the application
docker-compose up -d
```

## 🌐 Access Your Application

After deployment, your application will be available at:

- **Web Application**: http://localhost:8000
- **WebSocket Server**: http://localhost:8080 (for live chat)

## 📁 What's Included

### Docker Configuration Files
- `Dockerfile` - Main container configuration
- `docker-compose.yml` - Production deployment
- `docker-compose.dev.yml` - Development with hot reload
- `docker/supervisord.conf` - Process management
- `.dockerignore` - Ignore unnecessary files
- `docker-build.sh` - Automated build script

### Automated Setup Process
The Docker container automatically runs these commands:
1. ✅ `composer install` - Install PHP dependencies
2. ✅ `cp .env.example .env` - Copy environment file
3. ✅ `php artisan migrate --force` - Run database migrations
4. ✅ `php artisan key:generate` - Generate application key
5. ✅ `php artisan db:seed --force` - Seed database
6. ✅ `php artisan install:broadcasting --force` - Install broadcasting
7. ✅ `npm install && npm run build` - Install and build frontend assets
8. ✅ Starts Apache web server (`php artisan serve` equivalent)
9. ✅ `php artisan reverb:start` - Start WebSocket server
10. ✅ `php artisan queue:work --daemon` - Start queue worker

## 🔧 Development Mode

For development with hot reload:

```bash
# Use development compose file
docker-compose -f docker-compose.dev.yml up -d

# Or build development image
docker build -f Dockerfile.dev -t festvalley-dev .
```

## 📊 Container Architecture

```
┌─────────────────────────────────────┐
│           Supervisor                │
├─────────────────────────────────────┤
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ │
│  │ Apache  │ │ Reverb  │ │ Queue   │ │
│  │ :80     │ │ :8080   │ │ Worker  │ │
│  └─────────┘ └─────────┘ └─────────┘ │
└─────────────────────────────────────┘
│                                     │
│         Laravel Application         │
│            SQLite Database          │
└─────────────────────────────────────┘
```

## 🛠️ Useful Commands

### Container Management
```bash
# View running containers
docker-compose ps

# View application logs
docker-compose logs -f app

# Stop the application
docker-compose down

# Restart the application
docker-compose restart app

# Access container shell
docker exec -it festvalley-app bash
```

### Laravel Artisan Commands
```bash
# Run artisan commands inside container
docker exec -it festvalley-app php artisan migrate
docker exec -it festvalley-app php artisan db:seed
docker exec -it festvalley-app php artisan cache:clear
```

### Database Operations
```bash
# Access SQLite database
docker exec -it festvalley-app sqlite3 /var/www/html/database/database.sqlite

# Backup database
docker cp festvalley-app:/var/www/html/database/database.sqlite ./backup.sqlite

# Restore database
docker cp ./backup.sqlite festvalley-app:/var/www/html/database/database.sqlite
```

## 🔒 Production Deployment

### Environment Variables
For production, update these in `docker-compose.yml`:

```yaml
environment:
  - APP_ENV=production
  - APP_DEBUG=false
  - APP_URL=https://yourdomain.com
  - MAIL_MAILER=smtp
  - MAIL_HOST=your-smtp-host
  - MAIL_PORT=587
  - MAIL_USERNAME=your-email
  - MAIL_PASSWORD=your-password
```

### SSL/HTTPS Setup
Use a reverse proxy like Nginx or Traefik:

```yaml
# Add to docker-compose.yml
services:
  nginx:
    image: nginx:alpine
    ports:
      - "443:443"
      - "80:80"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/ssl/certs
    depends_on:
      - app
```

## 📈 Performance Optimization

### Production Optimizations Applied
- ✅ `composer install --optimize-autoloader --no-dev`
- ✅ `php artisan config:cache`
- ✅ `php artisan route:cache`
- ✅ `php artisan view:cache`
- ✅ `npm run build` (production assets)

### Scaling Options
```bash
# Scale queue workers
docker-compose up --scale app=1

# Multiple queue workers in supervisord.conf
numprocs=4  # In [program:queue-worker] section
```

## 🐛 Troubleshooting

### Common Issues

**Container won't start:**
```bash
# Check logs
docker-compose logs app

# Check if ports are available
lsof -i :8000
lsof -i :8080
```

**Permission errors:**
```bash
# Fix storage permissions
docker exec -it festvalley-app chown -R www-data:www-data storage
docker exec -it festvalley-app chmod -R 775 storage
```

**Database issues:**
```bash
# Reset database
docker exec -it festvalley-app php artisan migrate:fresh --seed
```

**WebSocket not working:**
```bash
# Check Reverb status
docker exec -it festvalley-app supervisorctl status reverb

# Restart Reverb
docker exec -it festvalley-app supervisorctl restart reverb
```

## 🔧 Customization

### Adding Custom Services
Edit `docker-compose.yml` to add services like Redis, MySQL, etc:

```yaml
services:
  app:
    # ... existing config
  
  redis:
    image: redis:alpine
    ports:
      - "6379:6379"
```

### Custom Environment Variables
Add to `.env` or `docker-compose.yml`:

```bash
# Custom variables
CUSTOM_API_KEY=your-api-key
EXTERNAL_SERVICE_URL=https://api.example.com
```

## 📋 Requirements Met

✅ **PHP 8.2+** with all required extensions  
✅ **SQLite database** ready and configured  
✅ **Composer dependencies** installed  
✅ **Node.js & NPM** for asset building  
✅ **Laravel Reverb** for WebSockets  
✅ **Queue workers** running in background  
✅ **Apache web server** configured  
✅ **Supervisor** for process management  

## 🎯 Next Steps

1. **Test the application**: Visit http://localhost:8000
2. **Check WebSocket**: Test live chat functionality
3. **Monitor logs**: `docker-compose logs -f app`
4. **Customize environment**: Update `.env` for your needs
5. **Deploy to production**: Use production docker-compose.yml

---

## 💡 Tips

- The SQLite database persists in `./database/database.sqlite`
- Application logs are in `/var/log/supervisor/` inside container
- Use `docker-compose.dev.yml` for development with hot reload
- Container automatically restarts on failure
- All Laravel setup commands run automatically on build
