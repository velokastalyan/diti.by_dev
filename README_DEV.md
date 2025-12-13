# diti.by dev environment

Local Docker setup for stabilizing the legacy PHP/Apache site.

## Quick start
1. Build and start containers:
   ```bash
   docker compose down -v
   docker compose up -d --build
   ```
2. Site will be available at http://localhost:8091/

## Configuration
- `APP_BASE_URL` (default: `http://localhost:8091`) — base URL used by the app for link generation.
- `APP_ENV` (default: `dev`) — enables verbose PHP logging when set to `dev`.
- `APACHE_VHOST_CONF` — path to the vhost file mounted into Apache. Defaults to `./docker/apache/000-default.conf`. To start in safe mode use:
  ```bash
  APACHE_VHOST_CONF=./docker/apache/000-default.safe.conf docker compose up -d --build
  ```

## Logs & diagnostics
- Docker logs:
  ```bash
  docker compose logs --tail=200 web
  ```
- Apache error log inside the container:
  ```bash
  docker compose exec -T web bash -lc 'tail -n 200 /var/log/apache2/error.log'
  ```
- One-shot health report:
  ```bash
  ./scripts/diag-web.sh
  ```

## Safe mode
- Main vhost: `docker/apache/000-default.conf` (serves `/pub` as static via Alias).
- Minimal fallback: `docker/apache/000-default.safe.conf` (no Alias, only DocumentRoot + Directory rules).
- Switch by exporting `APACHE_VHOST_CONF` before `docker compose up ...` as shown above.

## Rollback
If something goes wrong:
1. Revert code changes:
   ```bash
   git revert <commit-sha>
   # or reset the working copy
   git checkout .
   git clean -fd
   ```
2. Rebuild containers:
   ```bash
   docker compose down
   docker compose up -d --build
   ```
