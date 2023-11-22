#!/bin/sh
set -eu
echo "Tailing logs in /src/ASP/system/logs/*.log"
tail -n0 -F \
    /src/ASP/system/logs/admin_event.log \
    /src/ASP/system/logs/merge_players.log \
    /src/ASP/system/logs/php_errors.log \
    /src/ASP/system/logs/stats_debug.log \
    /src/ASP/system/logs/validate_awards.log \
    /src/ASP/system/logs/validate_ranks.log \
    2>/dev/null
