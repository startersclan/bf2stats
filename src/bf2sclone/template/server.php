<?php
$s = $server;
$template = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="inner">
<head>
	<title>'. esc_attr($s['data']['server']['hostname']) . ', ' . $TITLE .'</title>

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
	
		<h1 id="page-title">' . esc_attr($s['data']['server']['hostname']) . '</h1>
		<div id="page-3">
		
			<div id="content">
				<div id="content-id" style="white-space: nowrap;"><!-- template header end == begin content below -->
					<div style="display: inline-block; vertical-align: top; width: 66.7%; white-space: nowrap;">
						<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0;" class="stat server-stat">
						<tbody>
							<tr>
								<th colspan="999">SERVER INFO</th>
							</tr>
							<tr>
								<td class="column-key">Status</td>
								<td>' . esc_attr($s['data']['server']['gamemode']) . '</td>
								<td class="column-key">Players</td>
								<td>' . esc_attr("{$s['data']['server']['numplayers']}/{$s['data']['server']['maxplayers']}") . '</td>
							</tr>
							<tr>
								<td class="column-key">Dedicated</td>
								<td>' . esc_attr($s['data']['server']['bf2_dedicated']) . '</td>
								<td class="column-key">OS</td>
								<td>' . esc_attr($s['data']['server']['bf2_os']) . '</td>
							</tr>
							<tr>
								<td class="column-key">Version</td>
								<td>' . esc_attr($s['data']['server']['gamever']) . '</td>
								<td class="column-key">Mod</td>
								<td>' . esc_attr($s['data']['server']['gamevariant']) . '</td>
							</tr>
							<tr>
								<td class="column-key">Ranked</td>
								<td>' . esc_attr($s['data']['server']['bf2_ranked']) . '</td>
								<td class="column-key">Pure</td>
								<td>' . esc_attr($s['data']['server']['bf2_pure']) . '</td>
							</tr>
							<tr>
								<td class="column-key">BattleRecorder</td>
								<td>' . esc_attr($s['data']['server']['bf2_autorec']) . '</td>
								<td class="column-key">BattleCommo</td>
								<td>' . esc_attr($s['data']['server']['bf2_voip']) . '</td>
							</tr>
							<tr>
								<td class="column-key">Punkbuster</td>
								<td>' . esc_attr($s['data']['server']['bf2_anticheat']) . '</td>
								<td class="column-key">Password</td>
								<td>' . esc_attr($s['data']['server']['password']) . '</td>
							</tr>
							<tr>
								<td class="column-key">TK Mode</td>
								<td>' . esc_attr($s['data']['server']['bf2_tkmode']) . '</td>
								<td class="column-key">Vehicles</td>
								<td>' . esc_attr(($s['data']['server']['bf2_novehicles'] == 0 ? '1' : '0')) . '</td>
							</tr>
							<tr>
								<td class="column-key">Friendly Fire</td>
								<td>' . esc_attr($s['data']['server']['bf2_friendlyfire']) . '</td>
								<td class="column-key">Allowbalance</td>
								<td>' . esc_attr($s['data']['server']['bf2_autobalanced']) . '</td>
							</tr>
							<tr>
								<td class="column-key">Rounds per Map</td>
								<td>' . esc_attr($s['data']['server']['roundtime']) . '</td>
								<td class="column-key">Allow Global Unlocks</td>
								<td>' . esc_attr($s['data']['server']['bf2_autobalanced']) . '</td>
							</tr>
							<tr>
								<td class="column-key">Spawn Time</td>
								<td>' . esc_attr((int)$s['data']['server']['bf2_spawntime']) . '</td>
								<td class="column-key">Bot Ratio</td>
								<td>' . esc_attr(($s['data']['server']['bf2_coopbotratio'] ? $s['data']['server']['bf2_coopbotratio'] : '0')) . '</td>
							</tr>
								<td class="column-key">Time Limit</td>
								<td>' . esc_attr($s['data']['server']['timelimit']) . '</td>
								<td class="column-key">Number of Bots</td>
								<td>' . esc_attr(($s['data']['server']['bf2_coopbotcount'] ? $s['data']['server']['bf2_coopbotcount'] : '0')) . '</td>
							<tr>
								<td class="column-key">Ticket Ratio</td>
								<td>' . esc_attr($s['data']['server']['bf2_ticketratio']) . '</td>
								<td class="column-key">Bot Difficulty</td>
								<td>' . esc_attr(($s['data']['server']['bf2_coopbotdiff'] ? $s['data']['server']['bf2_coopbotdiff'] : '0')) . '</td>
							</tr>
							<tr>
								<td class="column-key">Team Ratio</td>
								<td>' . esc_attr((int)$s['data']['server']['bf2_teamratio']) . '</td>
								<td class="column-key"></td>
								<td></td>
							</tr>
							<tr>
								<td class="subheading" colspan="999">SERVER MESSAGE</td>
							</tr>
							<tr>
								<td style="height: 50px;  vertical-align: top;" colspan="999">' . $s['data']['server']['bf2_sponsortext'] . '</td>
							</tr>
						</tbody>
						</table>
					</div>
					';

					$map = str_replace(' ', '_', strtolower($s['data']['server']['mapname']));
					// devil's Perch Fix
					$map = str_replace('\'', '', $map);
					$mapUrl = file_exists(ROOT . "/game-images/levels/$map.png") ? "$ROOT/game-images/levels/$map.png" : "$ROOT/game-images/levels/default.png";
					$template .= '
					<div style="display: inline-block; vertical-align: top; width: 33.3%; white-space: nowrap;">
						<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0;" class="stat server-stat">
						<tbody>
							<tr>
								<th colspan="999">' . esc_attr($s['data']['server']['mapname']) . ' ' . esc_attr($s['data']['server']['bf2_mapsize']) . '</th>
							</tr>
							<tr>
								<td colspan="999">
									<img style="width: 100%; height: auto;" src="' . esc_attr($mapUrl) . '" />
								</td>
							</tr>
							</tr>
								<td class="subheading" colspan="999">MAP SETTINGS</td>
							</tr>
							<tr>
								<td class="column-key">Game Mode</td>
								<td>' . (preg_match('/gpm_coop/i', $s['data']['server']['gametype']) ? 'Co-op' : 'Conquest') . '</td>
							</tr>
							</tr>
								<td class="column-key">Map Size</td>
								<td>' . $s['data']['server']['bf2_mapsize'] . '</td>
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

					$template .= '
					<div style="margin-top: 10px; white-space: nowrap;">';
						$teams = array('team1', 'team2');
						foreach ($teams as $t) {
							$team = strtolower($s['data']['server']["bf2_$t"]);
							$template .= '
						<div style="display: inline-block; width: 50%; vertical-align: top;">
							<div style="display: block; border: 1px solid #111; padding: 5px; background: #383c33; color: #fff; font: normal 17px Verdana; text-align: center;">' . esc_attr(strtoupper(getArmyName($team))) . '</div>
							<img style="display: block; width: 100%; height: auto;" src="game-images/bigflags/bigflag_' . esc_attr($team) . '.png"/>
							<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0;" class="stat server-scoreboard-stat">
							<tbody>
								<tr>
									<th></th>
									<th>PLAYER NAME</th>
									<th><img src="game-images/playerIcons/overallscore_icon.png" alt="Overall Score" /></th>
									<!--<th><img src="game-images/scoreboard/scoreboard_icons_overallscore.png" alt="Overall Score" /></th>-->
									<th><img src="game-images/playerIcons/killscore_icon.png" alt="Kills" /></th>
									<!--<th><img src="game-images/scoreboard/scoreboard_icons_killscore.png" alt="killscore" /></th>-->
									<th><img src="game-images/playerIcons/deathscore_icon.png" alt="Deaths" /></th>
									<!--<th><img src="game-images/scoreboard/scoreboard_icons_deathscore.png" alt="deathscore" /></th>-->
									<th><img src="game-images/scoreboard/scoreboard_icons_ping.png" alt="Ping" /></th>
									<th>AI</th>
								</tr>
								';
								foreach ($s['data'][$t] as $k => $p) {
									$template .= '
								<tr>
									<td>' . esc_attr($k+1) . '</td>
									<td>' . '
										<img src="' . $ROOT . 'game-images/ranks/header/rank_' . esc_attr($p['rank']) . '.png" alt="' .  esc_attr(getRankByID($p['rank'])) . '" style="vertical-align: middle;" />
										<a href="' . "$ROOT?pid=" . esc_attr($p['pid']) . '" title="' . esc_attr($p['name']) . '" style="vertical-align: middle;">' . esc_attr($p['name']) . '</a>
									</td>
									<td>' . esc_attr($p['score']) . '</td>
									<td>' . esc_attr($p['kills']) . '</td>
									<td>' . esc_attr($p['deaths']) . '</td>
									<td>' . esc_attr($p['ping']) . '</td>
									<td>' . esc_attr($p['ai']) . '</td>
								</tr>';
								}
									$template .= '
							</tbody>
							</table>
						</div>
						';
						}

						$template .= '
					</div>

					<a id="secondhome" href="'.$ROOT.'"> </a>
					<!-- end content == footer below -->

					<hr class="clear">

				</div>
			</div> <!-- content-id --><!-- content -->
		</div>	<!-- Page 3 -->
		
		<div id="footer">This page was last updated {:LASTUPDATE:} ago. Next update will be in {:NEXTUPDATE:}<br>
		This page was processed in {:PROCESSED:} seconds.</div>
	
		<ul id="navitems">
			<li><a href="'. $ROOT .'">Home</a></li>
			<li><a href="'. $ROOT .'?go=servers">Servers</a></li>
		<li><a href="'. $ROOT .'?go=my-leaderboard">My Leader Board</a></li>

			<li><a href="'. $ROOT .'?go=currentranking">Rankings</a></li>
			<li><a href="'. $ROOT .'?go=ubar">UBAR</a></li>
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
