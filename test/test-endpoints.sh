#!/bin/sh
set -eu

echo
echo "[test-endpoints]"
ENDPOINTS="
asp.example.com/ 200
asp.example.com/.htaccess 401
asp.example.com/ASP/ 200
asp.example.com/ASP/frontend/template.php 401
asp.example.com/ASP/frontend/css/reset.css 200
asp.example.com/ASP/frontend/js/jquery-ui.js 200
asp.example.com/ASP/frontend/images/bf2logo.png 200
asp.example.com/ASP/frontend/css/fonts/ptsans/PTS55F-webfont.woff 200
asp.example.com/ASP/system 401
asp.example.com/ASP/bf2statistics.php 200
asp.example.com/ASP/getawardsinfo.aspx 200
asp.example.com/ASP/getbackendinfo.aspx 200
asp.example.com/ASP/getclaninfo.aspx 200
asp.example.com/ASP/getleaderboard.aspx 200
asp.example.com/ASP/getmapinfo.aspx 200
asp.example.com/ASP/getplayerid.aspx 200
asp.example.com/ASP/getplayerinfo.aspx 200
asp.example.com/ASP/getrankinfo.aspx 200
asp.example.com/ASP/getunlocksinfo.aspx 200
asp.example.com/ASP/index.php 200
asp.example.com/ASP/ranknotification.aspx 200
asp.example.com/ASP/searchforplayers.aspx 200
asp.example.com/ASP/selectunlock.aspx 200

bf2web.gamespy.com/ 200
bf2web.gamespy.com/.htaccess 401
bf2web.gamespy.com/ASP/ 200
bf2web.gamespy.com/ASP/frontend/template.php 401
bf2web.gamespy.com/ASP/frontend/css/reset.css 200
bf2web.gamespy.com/ASP/frontend/js/jquery-ui.js 200
bf2web.gamespy.com/ASP/frontend/images/bf2logo.png 200
bf2web.gamespy.com/ASP/frontend/css/fonts/ptsans/PTS55F-webfont.woff 200
bf2web.gamespy.com/ASP/system 401
bf2web.gamespy.com/ASP/bf2statistics.php 200
bf2web.gamespy.com/ASP/getawardsinfo.aspx 200
bf2web.gamespy.com/ASP/getbackendinfo.aspx 200
bf2web.gamespy.com/ASP/getclaninfo.aspx 200
bf2web.gamespy.com/ASP/getleaderboard.aspx 200
bf2web.gamespy.com/ASP/getmapinfo.aspx 200
bf2web.gamespy.com/ASP/getplayerid.aspx 200
bf2web.gamespy.com/ASP/getplayerinfo.aspx 200
bf2web.gamespy.com/ASP/getrankinfo.aspx 200
bf2web.gamespy.com/ASP/getunlocksinfo.aspx 200
bf2web.gamespy.com/ASP/index.php 200
bf2web.gamespy.com/ASP/ranknotification.aspx 200
bf2web.gamespy.com/ASP/searchforplayers.aspx 200
bf2web.gamespy.com/ASP/selectunlock.aspx 200

bf2sclone.example.com/ 200
bf2sclone.example.com/?go= 200
bf2sclone.example.com/?go=currentranking 200
bf2sclone.example.com/?go=leaderboard 200
bf2sclone.example.com/?go=my-leaderboard 200
bf2sclone.example.com/?go=servers 200
bf2sclone.example.com/?go=ubar 200
bf2sclone.example.com/?go=ubar&p=ribbons 200
bf2sclone.example.com/?go=ubar&p=ribbons-sf 200
bf2sclone.example.com/?go=ubar&p=badges 200
bf2sclone.example.com/?go=ubar&p=badges-sf 200
bf2sclone.example.com/?go=ubar&p=medals 200
bf2sclone.example.com/?go=ubar&p=medals-sf 200
bf2sclone.example.com/?go=ubar&p=ranks 200
bf2sclone.example.com/?go=foo 404
bf2sclone.example.com/.htaccess 401
bf2sclone.example.com/cache 401
bf2sclone.example.com/css/default.css 200
bf2sclone.example.com/game-images/armies/0.png 200
bf2sclone.example.com/js/nt2.js 200
bf2sclone.example.com/queries/armies.list 401
bf2sclone.example.com/queries/getArmiesByPID.php 401
bf2sclone.example.com/site-images/online.png 200
bf2sclone.example.com/template/config.inc.php.template 401
bf2sclone.example.com/awards.inc.php 401
bf2sclone.example.com/install.php.disabled 401
"
command -v curl || apk add --no-cache curl
echo "$ENDPOINTS" | awk NF | while read -r i j; do
    d=$( echo "$i" | cut -d '/' -f1 )
    if curl --head -skL http://$i --resolve $d:80:127.0.0.1 --resolve $d:443:127.0.0.1 2>&1 | grep -E "^HTTP/(1.1|2) $j " > /dev/null; then
        echo "PASS: $i"
    else
        echo "FAIL: $i"
        exit 1
    fi
done
