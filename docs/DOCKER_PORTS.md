# Docker Port Configuration

## Current Setup

The application is configured to run on **port 80** (standard HTTP port), allowing access via `http://localhost` without specifying a port number.

## Configuration

### docker-compose.yml
```yaml
web:
  image: nginx:1.27-alpine
  container_name: yejara_web
  ports:
    - "80:80"  # Host:Container
```

**Explanation:**
- `80:80` maps host port 80 to container port 80
- Nginx inside container listens on port 80
- Host machine exposes port 80
- Access: `http://localhost` (no port needed)

## Access Points

### Web Application
- **URL**: http://localhost
- **Swagger UI**: http://localhost/api/documentation
- **Telescope**: http://localhost/telescope

### Other Services
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

## Alternative Port Configurations

### Option 1: Port 8080 (Alternative HTTP)
```yaml
ports:
  - "8080:80"
```
Access: http://localhost:8080

### Option 2: Port 3000 (Common for Node.js)
```yaml
ports:
  - "3000:80"
```
Access: http://localhost:3000

### Option 3: Port 443 (HTTPS)
```yaml
ports:
  - "443:443"
```
Access: https://localhost (requires SSL configuration)

## Changing Ports

1. Edit `docker-compose.yml`
2. Change the port mapping:
   ```yaml
   ports:
     - "YOUR_PORT:80"
   ```
3. Restart Docker:
   ```bash
   docker compose down
   docker compose up -d
   ```

## Port Conflicts

### Error: "Port 80 is already in use"

**Common causes:**
- Another web server running (Apache, IIS, XAMPP)
- Another Docker container using port 80
- Windows services using port 80

**Solutions:**

#### 1. Stop conflicting service
```powershell
# Check what's using port 80
netstat -ano | findstr :80

# Stop IIS (if running)
iisreset /stop

# Stop Apache/XAMPP
# Use their control panels
```

#### 2. Use different port
Change to port 8080:
```yaml
ports:
  - "8080:80"
```

#### 3. Stop Docker containers
```bash
# Stop all containers
docker stop $(docker ps -aq)

# Or stop specific container
docker stop yejara_web
```

## Environment-Specific Ports

### Development (Current)
- Port: 80
- URL: http://localhost

### Local PHP Server
- Port: 8000 (default for `php artisan serve`)
- URL: http://localhost:8000

### Production
- Port: 80 (HTTP) or 443 (HTTPS)
- URL: https://api.yejara.com

## Nginx Configuration

The Nginx configuration inside the container is located at:
- Container path: `/etc/nginx/conf.d/default.conf`
- Host path: `./docker/nginx/default.conf`

Nginx listens on port 80 inside the container and forwards requests to PHP-FPM.

## Best Practices

1. **Development**: Use port 80 for convenience (no port in URL)
2. **Multiple projects**: Use different ports (8080, 8081, etc.)
3. **Production**: Use port 80 (HTTP) and 443 (HTTPS)
4. **Security**: Don't expose database ports in production

## Troubleshooting

### Cannot access http://localhost

1. **Check Docker is running:**
   ```bash
   docker ps
   ```

2. **Check web container is up:**
   ```bash
   docker compose ps web
   ```

3. **Check logs:**
   ```bash
   docker compose logs web
   ```

4. **Restart containers:**
   ```bash
   docker compose restart web
   ```

### Port 80 requires admin on Windows

On Windows, port 80 may require administrator privileges.

**Solution:**
- Run Docker Desktop as administrator, OR
- Use port 8080 instead

## Summary

- ✅ **Current**: Port 80 → Access via `http://localhost`
- ✅ **No port number needed** in URL
- ✅ **Standard HTTP port** for web applications
- ✅ **Easy to remember** and share

To change ports, edit `docker-compose.yml` and restart Docker.
