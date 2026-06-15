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

## Production Deployment (Hetzner)

**Server**: `178.156.245.46` (Hetzner CPX21, 3.7GB RAM, Ubuntu 22.04)
**URL**: https://map.mutualaid.nyc
**App Path**: `/opt/wegovmaps/`

### Architecture
```
Browser → Docker Nginx (:80/:443)
            ├── static files from /opt/wegovmaps (volume mount)
            └── *.php → host PHP-FPM (172.17.0.1:9001)
                          └── MySQL (127.0.0.1:3306, wegov_map)

Cron (hourly) → cacheupdate.php
                  ├── Airtable API → MySQL sync
                  └── Generates coverage_cache.json + nta_list_cache.json
```

### PHP-FPM Memory Tuning (Critical)

> **⚠️ OOM Issue**: On shared servers with limited RAM, PHP-FPM's default `pm = dynamic`
> keeps idle workers alive, which can cause the OS to OOM-kill the process overnight.

The pool config at `/etc/php/8.1/fpm/pool.d/wegovmaps.conf` must use **`pm = ondemand`**:
```ini
[wegovmaps]
user = www-data
group = www-data
listen = 0.0.0.0:9001
pm = ondemand              ; Only spawn workers when needed
pm.max_children = 3        ; Max 3 concurrent PHP workers
pm.process_idle_timeout = 30s  ; Kill idle workers after 30s
pm.max_requests = 200      ; Recycle workers to prevent memory leaks
php_admin_value[memory_limit] = 64M
```

**Do NOT** use `pm = dynamic` or `pm = static` on this server — the other services
(Strapi, CTFG, Portland OCDS, Services app) share the same 3.7GB of RAM.

A systemd override at `/etc/systemd/system/php8.1-fpm.service.d/restart.conf` ensures
PHP-FPM auto-restarts after OOM kills (max ~5s downtime instead of staying down):
```ini
[Service]
Restart=always
RestartSec=5
OOMPolicy=continue
```

### Caching Strategy

The cron job (`cron/cacheupdate.php`) generates two static JSON files that eliminate
live Airtable API calls at request time:

| File | Purpose | Without cache |
|---|---|---|
| `data/nta_list_cache.json` | NTA code → name mapping | ~3s (Airtable API) |
| `data/coverage_cache.json` | Which NTAs have groups | ~6.5s (Airtable API) |

If these files are missing, the app falls back to live API calls (slow but functional).

### SSL Auto-Renewal (Critical)

> **⚠️ Port 80 conflict**: The cert uses certbot's `standalone` authenticator, which must
> bind port 80 — but Docker nginx (`opt-nginx-1`) holds it. So a plain `certbot renew` from
> the timer silently fails for this cert (it failed unnoticed and the cert expired 2026-06-07).

The `map.mutualaid.nyc` renewal config (`/etc/letsencrypt/renewal/map.mutualaid.nyc.conf`)
has **per-cert hooks** so the automatic certbot timer can renew unattended:
```ini
pre_hook = docker stop opt-nginx-1
post_hook = docker start opt-nginx-1
renew_hook = cp /etc/letsencrypt/live/map.mutualaid.nyc/fullchain.pem /opt/nginx/certs/map.mutualaid.nyc/ && cp /etc/letsencrypt/live/map.mutualaid.nyc/privkey.pem /opt/nginx/certs/map.mutualaid.nyc/
```
These MUST stay **per-cert** (not in `/etc/letsencrypt/renewal-hooks/`) — a global pre-hook
stopping nginx would break `portland-ocds.wegov.nyc`, which renews via `webroot` and needs
nginx **up**. Verify with: `certbot renew --cert-name map.mutualaid.nyc --dry-run`.

`setup-ssl.sh` remains the manual one-shot fallback (does the same stop/renew/copy/start).

### Troubleshooting

| Symptom | Likely Cause | Fix |
|---|---|---|
| 502 Bad Gateway | PHP-FPM crashed (OOM) | `systemctl restart php8.1-fpm` |
| Map not showing | Mapbox token missing | Set token in `js/script_covid.js` on server |
| Stale data | Cron not running | `cd /opt/wegovmaps/cron && php cacheupdate.php` |
| SSL expired | Auto-renew hooks missing/broken | Run `/opt/wegovmaps/setup-ssl.sh` now; verify hooks per "SSL Auto-Renewal" above |
