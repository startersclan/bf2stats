#!/bin/sh
set -eu

echo
echo "[test-routes]"
URLS="
http://asp/ 200
http://asp/.htaccess 401
http://asp/ASP/ 200
http://asp/ASP/frontend/template.php 401
http://asp/ASP/frontend/css/reset.css 200
http://asp/ASP/frontend/js/jquery-ui.js 200
http://asp/ASP/frontend/images/bf2logo.png 200
http://asp/ASP/frontend/css/fonts/ptsans/PTS55F-webfont.woff 200
http://asp/ASP/system 401
http://asp/ASP/bf2statistics.php 200
http://asp/ASP/getawardsinfo.aspx 200
http://asp/ASP/getbackendinfo.aspx 200
http://asp/ASP/getclaninfo.aspx 200
http://asp/ASP/getleaderboard.aspx 200
http://asp/ASP/getmapinfo.aspx 200
http://asp/ASP/getplayerid.aspx 200
http://asp/ASP/getplayerinfo.aspx 200
http://asp/ASP/getrankinfo.aspx 200
http://asp/ASP/getunlocksinfo.aspx 200
http://asp/ASP/index.php 200
http://asp/ASP/ranknotification.aspx 200
http://asp/ASP/searchforplayers.aspx 200
http://asp/ASP/selectunlock.aspx 200

http://bf2sclone/ 200
http://bf2sclone/.htaccess 401
http://bf2sclone/cache 401
http://bf2sclone/css/default.css 200
http://bf2sclone/game-images/armies/0.png 200
http://bf2sclone/js/nt2.js 200
http://bf2sclone/queries/armies.list 401
http://bf2sclone/queries/getArmiesByPID.php 401
http://bf2sclone/site-images/online.png 200
http://bf2sclone/template/config.inc.php.template 401
http://bf2sclone/awards.inc.php 401
http://bf2sclone/install.php.disabled 401

http://phpmyadmin/ 200
"
echo "$URLS" | awk NF | while read -r i j; do
    if wget -q -SO- "$i" 2>&1 | grep "HTTP/1.1 $j " > /dev/null; then
        echo "PASS: $i"
    else
        echo "FAIL: $i"
        exit 1
    fi
done
