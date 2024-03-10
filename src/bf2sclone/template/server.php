<?php
$s = $server;
$template = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="inner">
<head>
	<title>' . ($s['data'] ? esc_attr($s['data']['server']['hostname']) : esc_attr($s['name'])) . ', ' . esc_attr(TITLE) . '</title>

	<link rel="icon" href="'.$ROOT.'favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="'.$ROOT.'favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" media="screen" href="'.$ROOT.'css/two-tiers.css">
	<link rel="stylesheet" type="text/css" media="screen" href="'.$ROOT.'css/nt.css">
	<link rel="stylesheet" type="text/css" media="print" href="'.$ROOT.'css/print.css">
	<link rel="stylesheet" type="text/css" media="screen" href="'.$ROOT.'css/default.css">

	<script type="text/javascript">/* no frames */ if(top.location != self.location) top.location.replace(self.location);</script>
	<script type="text/javascript" src="'. $ROOT .'js/nt2.js"></script>
	<script type="text/javascript" src="'. $ROOT .'js/show.js"></script>
</head>

<body class="inner">
<div id="page-1">
	<div id="page-2">
	
		<h1 id="page-title">' . ($s['data'] ? esc_attr($s['data']['server']['hostname']) : esc_attr($s['name'])) . '</h1>
		<div id="page-3">
		
			<div id="content">
				<div id="content-id" style="white-space: nowrap;"><!-- template header end == begin content below -->
					<div style="display: inline-block; vertical-align: top; width: 66.7%; white-space: nowrap;">'; 
						$publicaddress = $publicip = $s['ip'];
						if (isset($s['publicaddress'])) {
							if (filter_var($s['publicaddress'], FILTER_VALIDATE_IP)) {
								$publicaddress = $publicip = $s['publicaddress'];
							}else if ($s['publicaddress']) {
								$dnsRecords = dns_get_record($s['publicaddress'], DNS_A);
								if ($dnsRecords && count($dnsRecords) > 0) {
									$publicaddress = $s['publicaddress'];
									$publicip = $dnsRecords[0]['ip'];
								}
							}
						}
						$port = $s['port'] ? $s['port'] : '0';
						$template .= '
						<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0;" class="stat server-stat">
						<tbody>
							<tr>
								<th colspan="2">SERVER INFO</th>
								<th colspan="2" style="text-align: right;">(<span alt="' . esc_attr("$publicaddress:$port") . '">' . esc_attr("$publicip:$port") . '</span>)</th>
							</tr>
							<tr>
								<td class="column-key">Status</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['gamemode']) : 'OFFLINE') . '</td>
								<td class="column-key">Players</td>
								<td>' . ($s['data'] ? esc_attr("{$s['data']['server']['numplayers']}/{$s['data']['server']['maxplayers']}") : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Dedicated</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_dedicated']) : '-') . '</td>
								<td class="column-key">OS</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_os']) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Version</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['gamever']) : '-') . '</td>
								<td class="column-key">Mod</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['gamevariant']) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Ranked</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_ranked']) : '-') . '</td>
								<td class="column-key">Pure</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_pure']) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">BattleRecorder</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_autorec']) : '-') . '</td>
								<td class="column-key">BattleCommo</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_voip']) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Punkbuster</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_anticheat']) : '-') . '</td>
								<td class="column-key">Password</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['password']) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">TK Mode</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_tkmode']) : '-') . '</td>
								<td class="column-key">Vehicles</td>
								<td>' . ($s['data'] ? esc_attr(($s['data']['server']['bf2_novehicles'] == 0 ? '1' : '0')) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Friendly Fire</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_friendlyfire']) : '-') . '</td>
								<td class="column-key">Allowbalance</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_autobalanced']) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Rounds per Map</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['roundtime']) : '-') . '</td>
								<td class="column-key">Allow Global Unlocks</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_autobalanced']) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Spawn Time</td>
								<td>' . ($s['data'] ? esc_attr((int)$s['data']['server']['bf2_spawntime']) : '-') . '</td>
								<td class="column-key">Bot Ratio</td>
								<td>' . ($s['data'] ? esc_attr(($s['data']['server']['bf2_coopbotratio'] ? $s['data']['server']['bf2_coopbotratio'] : '0')) : '-') . '</td>
							</tr>
								<td class="column-key">Time Limit</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['timelimit']) : '-') . '</td>
								<td class="column-key">Number of Bots</td>
								<td>' . ($s['data'] ? esc_attr(($s['data']['server']['bf2_coopbotcount'] ? $s['data']['server']['bf2_coopbotcount'] : '0')) : '-') . '</td>
							<tr>
								<td class="column-key">Ticket Ratio</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_ticketratio']) : '-') . '</td>
								<td class="column-key">Bot Difficulty</td>
								<td>' . ($s['data'] ? esc_attr(($s['data']['server']['bf2_coopbotdiff'] ? $s['data']['server']['bf2_coopbotdiff'] : '0')) : '-') . '</td>
							</tr>
							<tr>
								<td class="column-key">Team Ratio</td>
								<td>' . ($s['data'] ? esc_attr((int)$s['data']['server']['bf2_teamratio']) : '-') . '</td>
								<td class="column-key"></td>
								<td></td>
							</tr>
							<tr>
								<td class="subheading" colspan="999">SERVER MESSAGE</td>
							</tr>
							<tr>
								<td style="height: 50px;  vertical-align: top;" colspan="999">' . ($s['data'] ? esc_attr($s['data']['server']['bf2_sponsortext']) : '') . '</td>
							</tr>
						</tbody>
						</table>
					</div>
					';

					$map = str_replace(' ', '_', ($s['data'] ? strtolower($s['data']['server']['mapname']) : ''));
					// devil's Perch Fix
					$map = str_replace('\'', '', $map);
					$mapSrc = file_exists(ROOT . "/game-images/levels/$map.png") ? "$ROOT/game-images/levels/$map.png" : "$ROOT/game-images/levels/default.png";
					$template .= '
					<div style="display: inline-block; vertical-align: top; width: 33.3%; white-space: nowrap;">
						<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0;" class="stat server-stat">
						<tbody>
							<tr>
								<th colspan="999">' . ($s['data'] ? esc_attr($s['data']['server']['mapname']) : '-') . '</th>
							</tr>
							<tr>
								<td colspan="999">
									<img style="width: 100%; height: auto;" src="' . esc_attr($mapSrc) . '" />
								</td>
							</tr>
							</tr>
								<td class="subheading" colspan="999">MAP SETTINGS</td>
							</tr>
							<tr>
								<td class="column-key">Game Mode</td>
								<td>' . ($s['data'] ? (preg_match('/gpm_coop/i', $s['data']['server']['gametype']) ? 'Co-op' : 'Conquest') : '-') . '</td>
							</tr>
							</tr>
								<td class="column-key">Map Size</td>
								<td>' . ($s['data'] ? esc_attr($s['data']['server']['bf2_mapsize']) : '') . '</td>
							</tr>
							';
							$graphicUrl = $s['data']['server']['bf2_sponsorlogo_url'];
							if (!$graphicUrl) { 
								$graphicUrl = $s['data']['server']['bf2_communitylogo_url'];
							}
							$template .= '
							</tr>
								<td class="subheading" colspan="999">SERVER GRAPHIC</td>
							</tr>
							<tr>
								<td style="height: 50px;" colspan="999"><img style="max-width: 100%; height: auto;" src="' . esc_attr($graphicUrl) . '" /></td>
							</tr>
						</tbody>
						</table>
					</div>
					';

					if ($s['data']) {
						$template .= '
					<div style="margin-top: 10px; white-space: nowrap;">';
						$teams = array('team1', 'team2');
						foreach ($teams as $t) {
							$team = strtolower($s['data']['server']["bf2_$t"]);
							$template .= '
						<div style="display: inline-block; width: 50%; vertical-align: top;">
							<div style="display: block; border: 1px solid #111; padding: 5px; background: #383c33; color: #fff; font: normal 17px Verdana; text-align: center;">' . esc_attr(strtoupper(getArmyName($team))) . '</div>
							<img style="display: block; width: 100%; height: auto;" src="game-images/bigflags/bigflag_' . esc_attr($team) . '.png"/>
							<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0;" class="stat server-scoreboard-stat sortable">
							<tbody>
								<tr>
									<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;">#<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></th>
									<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;">' . (RANKING_PIDS_AS_NAMES ? 'PID' : 'PLAYER NAME') . '<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
									<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;"><img src="game-images/playerIcons/overallscore_icon.png" alt="Overall Score" /><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
									<!--<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;"><img src="game-images/scoreboard/scoreboard_icons_overallscore.png" alt="Overall Score" /><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>-->
									<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;"><img src="game-images/playerIcons/killscore_icon.png" alt="Kills" /><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
									<!--<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;"><img src="game-images/scoreboard/scoreboard_icons_killscore.png" alt="killscore" /><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>-->
									<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;"><img src="game-images/playerIcons/deathscore_icon.png" alt="Deaths" /><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
									<!--<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;"><img src="game-images/scoreboard/scoreboard_icons_deathscore.png" alt="deathscore" /><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>-->
									<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;"><img src="game-images/scoreboard/scoreboard_icons_ping.png" alt="Ping" /><span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
									<th><a href="#" class="sortheader" onclick="ts_resortTable(this); return false;">AI<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a></th>
								</tr>
								';
								foreach ($s['data'][$t] as $k => $p) {
									$template .= '
								<tr>
									<td>' . ($s['data'] ? esc_attr($k+1) : '-') . '</td>
									<td>' . '
										<img src="' . $ROOT . 'game-images/ranks/header/rank_' . esc_attr($p['rank']) . '.png" alt="' .  esc_attr(getRankByID($p['rank'])) . '" style="vertical-align: middle;" />
										<a href="' . "$ROOT?pid=" . esc_attr($p['pid']) . '" title="' . esc_attr(RANKING_PIDS_AS_NAMES ? $p['pid'] : $p['name']) . '" style="vertical-align: middle;">' . esc_attr(RANKING_PIDS_AS_NAMES ? $p['pid'] : $p['name']) . '</a>
									</td>
									<td>' . ($s['data'] ? esc_attr($p['score']) : '-') . '</td>
									<td>' . ($s['data'] ? esc_attr($p['kills']) : '-') . '</td>
									<td>' . ($s['data'] ? esc_attr($p['deaths']) : '-') . '</td>
									<td>' . ($s['data'] ? esc_attr($p['ping']) : '-') . '</td>
									<td>' . ($s['data'] ? esc_attr($p['ai']) : '-') . '</td>
								</tr>';
								}
									$template .= '
							</tbody>
							</table>
						</div>
						';
						}
					}

					$template .= '
					</div>

					<a id="secondhome" href="'.$ROOT.'"> </a>
					<!-- end content == footer below -->

					<hr class="clear">

				</div>
			</div> <!-- content-id --><!-- content -->
		</div>	<!-- Page 3 -->
		
		<div id="footer">This page was last updated {:LASTUPDATE:} ago. Next update will be in {:NEXTUPDATE:}<br>' .
		(FOOTER_PAGELOADSPEED_ENABLE ? 'This page was processed in {:PROCESSED:} seconds.</div>' : '') . '
	
		<ul id="navitems">
			<li class="' . ($GO == 'leaderboard' ? 'current' : '') . '"><a href="'. $ROOT .'?go=leaderboard">Leaderboard</a></li>
			<li class="' . ($GO == 'servers' ? 'current' : '') . '"><a href="'. $ROOT .'?go=servers">Servers</a></li>
			<li class="' . ($GO == 'my-leaderboard' ? 'current' : '') . '"><a href="'. $ROOT .'?go=my-leaderboard">My Leader Board</a></li>
			<li class="' . ($GO == 'currentranking' ? 'current' : '') . '"><a href="'. $ROOT .'?go=currentranking">Rankings</a></li>
			<li class="' . ($GO == 'ubar' ? 'current' : '') . '"><a href="'. $ROOT .'?go=ubar">UBAR</a></li>
			<li><a href="http://wiki.bf2s.com/">Wiki</a></li>
		</ul>
		
		<form action="'.$ROOT.'?go=search" method="post" id="getstats">
			<label for="pid">Get Stats</label>
			<input type="text" name="searchvalue" id="pid" value="" />
			<input type="submit" class="btn" value="Go" />
		</form>
		
	</div><!-- page 2 -->
</div>
</body>
</html>';
?>
