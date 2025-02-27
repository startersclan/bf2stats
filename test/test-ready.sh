#!/bin/sh
set -eu

echo
echo "[test-ready] Waiting for stack to be ready"
s=0
while true; do
    nc -vz -w 1 asp 80 \
    && nc -vz -w 1 asp 9000 \
    && nc -vz -w 1 bf2sclone 80 \
    && nc -vz -w 1 bf2sclone 9000 \
    && nc -vz -w 1 db 3306 \
    && break || true
    s=$(( $s + 1 ))
    if [ "$s" -eq 100 ]; then
        exit 1
    fi
    echo "Retrying in 3 seconds"
    sleep 3
done
