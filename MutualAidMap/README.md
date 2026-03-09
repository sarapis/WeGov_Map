# Mutual Aid NYC Map

A standalone map application for displaying Mutual Aid Groups in NYC, powered by Airtable.

## Setup

1.  **Database**
    - Create a MySQL database (e.g., `wegov_map`).
    - Import the schema:
      ```bash
      mysql -u user -p wegov_map < data/schema.sql
      ```

2.  **Configuration**
    - Copy the sample config:
      ```bash
      cp config/config.sample.php config/config.php
      ```
    - Edit `config/config.php` with your Database credentials, Airtable API keys, and **Mapbox Access Token**.
    - **Important**: You also need to paste your Mapbox Public Token into `js/script_covid.js`. Look for:
      ```javascript
      mapboxgl.accessToken = 'REPLACE_WITH_YOUR_MAPBOX_TOKEN';
      ```

3.  **Data Sync**
    - The map relies on a local cache of the Airtable data. Run the update script to populate it:
      ```bash
      php cron/cacheupdate.php
      ```
    - Set up a cron job to run `cron/update_cache.sh` periodically (e.g., hourly).

4.  **Run**
    - Serve the root directory using PHP (Apache/Nginx/PHP-CLI):
      ```bash
      php -S localhost:8000
      ```
    - Visit `http://localhost:8000/index.php`.

## Project Structure

- `cron/`: Data synchronization scripts.
- `js/`, `css/`, `images/`: Frontend assets.

## Deployment Notes

### Permissions
Ensure the `images/covid_groups/` directory is writable by the user running the cron job/web server:
```bash
chmod 775 images/covid_groups/
```

### HTTPS (SSL)
This application is designed to run behind HTTPS.
- Use **Certbot** for free SSL certificates:
  ```bash
  sudo certbot --apache -d map.yourdomain.com
  ```

### Performance Optimization
To ensure fast load times for the map polygons (GeoJSON), add the following to your Apache VirtualHost configuration:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE application/json application/geo+json
</IfModule>
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType application/geo+json "access plus 1 week"
</IfModule>
```
