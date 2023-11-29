# Upgrade docker images to v2.6.x

In <= v2.5.x, `asp` and `bf2sclone` each had separate `nginx` and `php` images.

Since v2.6.0: `asp` and `bf2sclone` each contains both `nginx` and `php`, with environment variable support, and entrypoint that sets the correct permissions.

Benefits:

- Easier to deploy / upgrade. No need to separate `nginx` and `php` containers
- Environment variable configuration means no more need to mount config into `asp` and `bf2sclone` containers
- Entrypoint script sets permissions on volumes. `init-container` should only need to set permissions for `db` volume

## Upgrade steps

These steps are demonstrated using Docker Compose.

1. Merge the networks and volumes of `asp-nginx` and `asp-php` into a single `asp` container, switch to env vars and a volume for `asp` configuration, and remove `depends_on`.

For instance, from this:

```yaml
  asp-nginx:
    image: startersclan/bf2stats:2.5.1-asp-nginx
    volumes:
      - ./config/ASP/nginx/nginx.conf:/etc/nginx/nginx.conf:ro
    networks:
      traefik-network:
      bf2-network:
        aliases:
          - asp.example.com # For the ASP System Tests to resolve to itself
          - bf2web.gamespy.com # Spoof gamespy DNS for the BF2 server connected to this network
    depends_on:
      - init-container
      - asp-php

  asp-php:
    image: startersclan/bf2stats:2.5.1-asp-php
    volumes:
      - ./config/ASP/config.php:/src/ASP/system/config/config.php # Main config file. Must be writeable or else ASP will throw an exception. Customize only if needed
      - backups-volume:/src/ASP/system/database/backups # This volume is effectively unused since ASP doesn't allow DB backups for a remote DB, but mount it anyway to avoid errors.
      - logs-volume:/src/ASP/system/logs
      - snapshots-volume:/src/ASP/system/snapshots
    networks:
      - traefik-network
      - bf2-network
    depends_on:
      - init-container
```

To this:

```yaml
  asp:
    image: startersclan/bf2stats:2.6.0-asp
    environment:
      # See ./src/ASP/system/config/config.php for all supported env vars. Use comma-delimited value for array
      - DB_HOST=db
      - DB_PORT=3306
      - DB_NAME=bf2stats
      - DB_USER=admin
      - DB_PASS=admin
      - ADMIN_HOSTS=127.0.0.1,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16   # Limit admins to private IPs
      - GAME_HOSTS=127.0.0.1,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16    # Limit gameservers to private IPs
      - DEBUG_LVL=2
    volumes:
      - backups-volume:/src/ASP/system/database/backups # This volume is effectively unused since ASP doesn't allow DB backups for a remote DB, but mount it anyway to avoid errors.
      - config-volume:/src/ASP/system/config # For a stateful config file
      - logs-volume:/src/ASP/system/logs
      - snapshots-volume:/src/ASP/system/snapshots
    networks:
      traefik-network:
      bf2-network:
        aliases:
          - asp.example.com # For the ASP System Tests to resolve to itself
          - bf2web.gamespy.com # Spoof gamespy DNS for the BF2 server connected to this network

volumes:
  config-volume:
```

2. Merge the networks and volumes of `bf2sclone-nginx` and `bf2sclone-php` into a single `bf2sclone` container, switch to env vars for `bf2sclone` configuration, and remove `depends_on`.

For instance, from this:

```yaml
  bf2sclone-nginx:
    image: startersclan/bf2stats:2.5.1-bf2sclone-nginx
    depends_on:
      - init-container
      - bf2sclone-php
    networks:
      - traefik-network
      - bf2-network

  bf2sclone-php:
    image: startersclan/bf2stats:2.5.1-bf2sclone-php
    volumes:
      - ./config/bf2sclone/config.inc.php:/src/bf2sclone/config.inc.php:ro  # Main config file. Customize as needed
      - bf2sclone-cache-volume:/src/bf2sclone/cache
    networks:
      - bf2-network
    depends_on:
      - init-container
```

To this:

```yaml
  bf2sclone:
    image: startersclan/bf2stats:2.6.0-bf2sclone
    environment:
      # See ./src/bf2sclone/config.inc.php for all supported env vars
      - DBIP=db
      - DBNAME=bf2stats
      - DBLOGIN=admin
      - DBPASSWORD=admin
      # - TITLE=BF2S Clone
      # - RANKING_REFRESH_TIME=600
      # - RANKING_HIDE_BOTS=false
      # - RANKING_HIDE_HIDDEN_PLAYERS=false
      # - LEADERBOARD_COUNT=25
    volumes:
      - bf2sclone-cache-volume:/src/bf2sclone/cache
    networks:
      - traefik-network
      - bf2-network
```

3. If you have `init-container`, now it only needs to set permission for the `db` volume:

```yaml
  init-container:
    image: alpine:latest
    volumes:
      - db-volume:/var/lib/mysql
    entrypoint:
      - /bin/sh
    command:
      - -c
      - |
          set -eu

          echo "Granting db write permissions"
          chown -R 999:999 /var/lib/mysql
```

Done. Enjoy the simpler setup 😀
