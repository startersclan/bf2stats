# bf2stats

[![github-actions](https://github.com/startersclan/bf2stats/workflows/ci-master-pr/badge.svg)](https://github.com/startersclan/bf2stats/actions)
[![github-release](https://img.shields.io/github/v/release/startersclan/bf2stats?style=flat-square)](https://github.com/startersclan/bf2stats/releases/)
[![docker-image-size](https://img.shields.io/docker/image-size/startersclan/bf2stats/master-asp?label=asp)](https://hub.docker.com/r/startersclan/bf2stats)
[![docker-image-size](https://img.shields.io/docker/image-size/startersclan/bf2stats/master-bf2sclone?label=bf2sclone)](https://hub.docker.com/r/startersclan/bf2stats)

BF2Statistics [`2.x.x`](https://code.google.com/archive/p/bf2stats/) with docker support.

Although BF2Statistics [`3.1.0`](https://github.com/BF2Statistics/ASP) has been released, it is not backward compatible with `<= 2.x.x`. Hence, this project is to help those who want to retain their `2.x.x` stats system, and to ease deployment of the stack since support is scarce. It runs on PHP 7.4 with nginx.

## Usage (docker)

`asp` image (for support environment variables, see [here](./src/ASP/system/config/config.php)):

```sh
docker run --rm -it -p 80:80 -e DB_HOST=db -e DB_PORT=3306 -e DB_NAME=bf2stats -e DB_USER=admin -e DB_PASS=admin startersclan/bf2stats:2.9.3-asp
```

`bf2sclone` image (for supported environment variables, see [here](./src/bf2sclone/config.inc.php)):

```sh
docker run --rm -it -p 80:80 -e DBIP=db -e DBNAME=bf2stats -e DBLOGIN=admin -e DBPASSWORD=admin startersclan/bf2stats:2.9.3-bf2sclone
```

See [this](docs/full-bf2-stack-example) example showing how to deploy [Battlefield 2 1.5 server](https://github.com/startersclan/docker-bf2/), [PRMasterserver](https://github.com/startersclan/PRMasterServer) as the master server, and `bf2stats` as the stats web server, using `docker-compose`.

See [this](docs/bf2hub-bf2stats-example) example showing how to deploy [Battlefield 2 1.5 server](https://github.com/startersclan/docker-bf2/), with BF2Hub as the master server and `bf2stats` as the stats web server, using `docker-compose`.

### Upgrading (docker)

- For upgrades between `v2.2.0` and `v2.5.x`: Bump the docker image tags, login to the ASP and click follow the instructions to upgrade the DB
- For `<= v2.5.x`, see instructions to upgrade to `v2.6.x` [here](docs/upgrading-docker-images-to-2.6.md)
- For upgrades between `v2.6.0` and above: Bump the docker image tags, login to the ASP and click follow the instructions to upgrade the DB

## Development

To use Docker Compose v2, use `docker compose` instead of `docker-compose`.

```sh
# 1. Start
docker-compose up --build
# ASP available at http://localhost:8081/ASP. Username: admin, password admin. Login and set up the DB the first time. See ./config/ASP/config.php
# bf2sclone available at http://localhost:8082
# phpmyadmin available at http://localhost:8083. Username: admin, password: admin. See ./config/ASP/config.php config file

# 2. If you have just setup the DB the first time, restart the BF2 server to begin recording stats
docker-compose restart bf2

# 3. Before launching the BF2 client, spoof gamespy DNS by adding these entries in C:\Windows\system32\drivers\etc\hosts. This is needed for the BF2 client to work correctly.
# Replace '192.168.1.100' with your development machine's IP address
192.168.1.100 battlefield2.available.gamespy.com
192.168.1.100 battlefield2.master.gamespy.com
192.168.1.100 battlefield2.ms14.gamespy.com
192.168.1.100 master.gamespy.com
192.168.1.100 motd.gamespy.com
192.168.1.100 gpsp.gamespy.com
192.168.1.100 gpcm.gamespy.com
192.168.1.100 gamespy.com
192.168.1.100 bf2web.gamespy.com
192.168.1.100 gamestats.gamespy.com
192.168.1.100 eapusher.dice.se

# 4. Launch BF2 client and connect to the BF2 server
# - To use BF2Hub as the Gamespy server, launch BF2.exe, and login to your BF2Hub account, and connect to the BF2 server using MULTIPLAYER > CONNECT TO IP
# - To use PRMasterserver in docker-compose as the Gamespy server, if you have previously patched BF2.exe using the BF2Hub patcher, you must unpatch BF2.exe. Then launch BF2.exe (do not use BF2Hub), create a new Online account, login, and connect to the BF2 server using MULTIPLAYER > CONNECT TO IP.
# At the end of a round, the BF2 server will send a stats snapshot to the ASP. View stats in ASP and bf2sclone.

# Development - Install vscode extensions
# Once installed, set breakpoints in code, and press F5 to start debugging.
code --install-extension bmewburn.vscode-intelephense-client # PHP intellisense
code --install-extension xdebug.php-debug # PHP remote debugging via xdebug
code --install-extension ms-python.python # Python intellisense
# If xdebug is not working, iptables INPUT chain may be set to DROP on the docker bridge.
# Execute this to allow php to reach the host machine via the docker0 bridge
sudo iptables -A INPUT -i br+ -j ACCEPT

# BF2 server - Restart server
docker-compose restart bf2
# BF2 server - Attach to the bf2 server console
docker attach $( docker-compose ps -q bf2 )
# BF2 server - Exec into container
docker exec -it $( docker-compose ps -q bf2) bash
# BF2 server - Read logs
docker exec -it $( docker-compose ps -q bf2 ) bash -c 'cat python/bf2/logs/bf2game_*'
# BF2 server - List snapshots
docker exec -it $( docker-compose ps -q bf2 ) ls -alR python/bf2/logs/snapshots/unsent

# asp - Exec into container
docker exec -it $( docker-compose ps -q asp ) sh
# asp - List backups
docker exec -it $( docker-compose ps -q asp ) ls -al /src/ASP/system/database/backups
# asp - List config
docker exec -it $( docker-compose ps -q asp ) ls -al /src/ASP/system/config
# asp - Read logs
docker exec -it $( docker-compose ps -q asp ) ls -al /src/ASP/system/logs
# asp - List snapshots
docker exec -it $( docker-compose ps -q asp ) ls -alR /src/ASP/system/snapshots

# Test
./test/test.sh dev 1

# Test production builds 1
./test/test.sh prod1 1

# Test production builds 2
./test/test.sh prod2 1

# Test a snapshot
curl -s -A GameSpyHTTP/1.0 -H 'Content-Type: application/json' --data @test/snapshots/-test-snapshot.txt localhost:8081/ASP/bf2statistics.php

# Dump the DB
docker exec $( docker-compose ps -q db ) mysqldump -uroot -padmin bf2stats | gzip > bf2stats.sql.gz

# Restore the DB
zcat bf2stats.sql.gz | docker exec -i $( docker-compose ps -q db ) mysql -uroot -padmin bf2stats

# Stop
docker-compose down

# Cleanup
docker-compose down --remove-orphans
docker volume rm bf2stats_prmasterserver-volume
docker volume rm bf2stats_backups-volume
docker volume rm bf2stats_config-volume
docker volume rm bf2stats_logs-volume
docker volume rm bf2stats_snapshots-volume
docker volume rm bf2stats_bf2sclone-cache-volume
docker volume rm bf2stats_db-volume
```

## Release

```sh
./release.sh 2.x.x
git add .
git commit -m "Chore: Release 2.x.x"
```

## FAQ

### Q: ASP installer never completes the first time

A: This is caused by a bug where the UI fails to handle an invalid response from the backend. A `PHP_ERROR` `Warning: file_put_contents(/src/ASP/system/config/config.php): failed to open stream: Permission denied in /src/ASP/system/core/Config.php on line 165` is output before the JSON response causing invalid JSON.

Grant PHP write permission for `config.php`.

```sh
chmod 666 ./config/ASP/config.php
docker-compose restart asp
```

### Q: `Warning: file_put_contents(/src/ASP/system/config/config.php): failed to open stream: Permission denied in /src/ASP/system/core/Config.php on line 165` appearing in ASP dashboard

A: Grant the PHP user write permission for `./src/ASP/system/config/config.php`.

### Q: `There was an error testing the system. Please refresh the page and try again.` when using `System > Test System` in ASP

A: This is means the UI received an invalid JSON response from the backend. If you know how to, you can examine the payload of the `POST` response.

### Q: `BF2Statistics Processing Check: Fail` or ` Gamespy (.aspx) Basic Response: Fail` or `Gamespy (.aspx) Advanced (1) Response: Fail` when using `System > Test System` in ASP

A: DNS resolution problem. The `HOST` used in the test to test those Gamespy endpoints is the same host you see in your browser. For instance, if you are accessing the `ASP` using `http://yourdomain.com/`, `ASP` runs tests against `http://yourdomain.com/ASP/*.aspx`, which will may fail if DNS resolution for `yourdomain.com` fails.

If you see this in a development environment, simply ignore the errors. There is an integration test to those endpoints to ensure they work.

If you are seeing this in a production environment, use a fully qualified domain name (FQDN) so that `ASP` can resolve to its external DNS name to test against its external web endpoint.

### Q: `Importing Logs Failed!` when using `Server Admin > Import Logs` in ASP

A: Same as [this](#q-bf2statistics-processing-check-fail-or-gamespy-aspx-basic-response-fail-or-gamespy-aspx-advanced-1-response-fail-when-using-system--test-system-in-asp)

### Q: `Table (army) *NOT* Backed Up: [1045] Access denied for user 'admin'@'%' (using password: YES)` when using `System > Backup Database` in ASP

A: The `db` user does not have the `FILE` privilege. Add a grant manually. But note that even if you did, you still won't be able to backup without major security issues. See [here](#q-table-army-not-backed-up-1-cant-createwrite-to-file-when-using-system--backup-database-in-asp).

### Q: `Table (army) *NOT* Backed Up: [1] Can't create/write to file` when using `System > Backup Database` in ASP

The `backupdb` module uses [`SELECT * INTO OUTFILE`](https://mariadb.com/kb/en/select-into-outfile/), but the `src` files are not in the db container, `mariadb` cannot find the path to export the files. In the past, the `apache`, `php` and `mysql` ran on the same machine with write access to the same filesystem, but with `docker`, each container has its own filesystem. The only workaround is to mount the `backups-volume` inside the `db` container at the same path as it is mounted in the `ASP` container `/src/ASP/system/database/backups/`, with write permissions for `php`'s user `82` and `mariadb`'s user `999` which menas the directory needs `777` permissions (world writeable), which is very bad from the point of view of security.

It is better to backup the DB on a `cron` schedule using `mysqldump` from another container linked to the `db` container:

```sh
# Dump a DB at host `db`, user `root`, password `admin`, database `bf2stats`
mysqldump -hdb -uroot -padmin bf2stats
```

### Q: `Xdebug: [Step Debug] Could not connect to debugging client. Tried: host.docker.internal:9000 (through xdebug.client_host/xdebug.client_port)` appears in PHP logs on `docker-compose up`

A: If you are seeing this in development, the PHP debugger is not running. Press `F5` in `vscode` to start the PHP debugger. If you don't need debugging, set `XDEBUG_MODE=off` in `docker-compose.yml` to disable XDebug.
