<?php
$template = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="inner">
<head>
	<title>Servers, ' . esc_attr(TITLE) . '</title>

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
	
		<h1 id="page-title">Servers</h1>
		<div id="page-3">
			<div id="content">
				<div id="content-id"><!-- template header end == begin content below -->
				
					<center>
						<h2>Servers</h2>
						
						<table border="0" cellspacing="0" cellpadding="0" id="servers" class="stat servers sortable">
						<tbody>
							<tr>
								<th style="width: 10%;" class="nosort"></th>
								<th style="width: 25%;">SERVER NAME</th>
								<th>PLAYERS</th>
								<th>MAP</th>
								<th>SIZE </th>
								<th>MODE</th>
								<th>MOD</th>
								<th>VER</th>
							</tr>';
							
							foreach($servers as $s) {
								// Show only online servers?
								// if ($s['data'] === false) {
								// 	continue;
								// }
								
								$template .= '
							<tr>
								<td style="white-space: nowrap; cursor: default;">';
								if ($s['data'] === false) {
										$template .= '
									<span style="display: inline-block; vertical-align: middle; width: 10px; height: 10px; border: 0px solid #000; border-radius: 50%; background: #f6b620;" alt="OFFLINE"></span>
									<span style="vertical-align: middle; color: #F6B620;" alt="OFF">OFF</span>
									<img style="vertical-align: middle;" src="game-images/serverIcons/blank.png" alt="OFF" />
								</td>
								
								<td><a href="?go=servers&sid=' . esc_attr($s['id']) . '">' . esc_attr($s['name']) . '</a></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							</tr>';
								} else {
									$template .= '<span style="display: inline-block; vertical-align: middle; width: 10px; height: 10px; border: 0px solid #000; border-radius: 50%; background: #5add65;" alt="ON"></span>';
									$serverLoad = $s['data']['server']['numplayers'] / $s['data']['server']['maxplayers'];
									if ($serverLoad >= 0.66) {
										$template .= '<img src="game-images/serverIcons/Serverload_red.png" alt="High Load" />';
									} else if ($serverLoad >= 0.33) {
										$template .= '<img src="game-images/serverIcons/Serverload_orange.png" alt="Medium Load" />';
									} else {
										$template .= '<img src="game-images/serverIcons/Serverload_green.png" alt="Low Load" />';
									}

									if (preg_match('/^linux/', $s['data']['server']['bf2_os'])) {
										$template .= '<img src="game-images/serverIcons/linux.png" alt="Linux" />';
									} else {
										$template .= '<img src="game-images/serverIcons/Windows.png" alt="Windows" />';
									}

									if (preg_match('/-64$/', $s['data']['server']['bf2_os'])) {
										$template .= '<img src="game-images/serverIcons/64bit.png" alt="64-bit" />';
									} else {
										$template .= '<img src="game-images/serverIcons/Windows.png" alt="Not 64-bit" />';
									}
									
									if (file_exists(ROOT . "/game-images/mods/{$s['data']['server']['gamevariant']}/mod_icon.png")) {
										$mod = esc_attr($s['data']['server']['gamevariant']);
										$template .= '<img src="game-images/mods/' . esc_attr($mod) . '/mod_icon.png" alt="' . esc_attr($mod) . '" />';
									} else {
										$template .= '<img src="game-images/serverIcons/unknown_mod.png" alt="unknown_mod" />';
									}

									if ($s['data']['server']['bf2_ranked']) {
										$template .= '<img src="game-images/serverIcons/Ranked.png" alt="Ranked" />';
									} else {
										$template .= '<img src="game-images/serverIcons/blank.png" alt="Unranked" />';
									}

									if ($s['data']['server']['bf2_autorec']) {
										$template .= '<img src="game-images/serverIcons/battlerec.png" alt="BattleRecorder On" />';
									} else {
										$template .= '<img src="game-images/serverIcons/blank.png" alt="BattleRecorder Off" />';
									}

									if ($s['data']['server']['bf2_voip']) {
										$template .= '<img src="game-images/serverIcons/battleCom.png" alt="BattleCommo On" />';
									} else {
										$template .= '<img src="game-images/serverIcons/blank.png" alt="BattleCommo Off" />';
									}

									if ($s['data']['server']['bf2_anticheat']) {
										$template .= '<img src="game-images/serverIcons/punkBuster.png" alt="Punkbuster On" />';
									} else {
										$template .= '<img src="game-images/serverIcons/blank.png" alt="Punkbuster Off" />';
									}
									
									if ($s['data']['server']['bf2_pure']) {
										$template .= '<img src="game-images/serverIcons/PureContent.png" alt="Pure" />';
									} else {
										$template .= '<img src="game-images/serverIcons/blank.png" alt="Not Pure" />';
									}
									
									if ($s['data']['server']['password']) {
										$template .= '<img src="game-images/serverIcons/PasswordEnabled.png" alt="PasswordEnabled" />';
									} else {
										$template .= '<img src="game-images/serverIcons/blank.png" alt="No Password" />';
									}

									$map = str_replace(' ', '_', strtolower($s['data']['server']['mapname']));
									// devil's Perch Fix
									$map = str_replace('\'', '', $map);
									$mapUrl = file_exists(ROOT . "/game-images/levels/$map.png") ? "$ROOT/game-images/levels/$map.png" : "$ROOT/game-images/levels/default.png";
									$template .= '
								</td>
								<td><a href="?go=servers&sid=' . esc_attr($s['id']) . '">' . esc_attr($s['data']['server']['hostname']) . '</a></td>
								<td>' . esc_attr("{$s['data']['server']['numplayers']}/{$s['data']['server']['maxplayers']}"). '</td>
								<td> 
									<img src="' . esc_attr($mapUrl) . '" alt="' . esc_attr($map) . '" />' .
									' ' . esc_attr($s['data']['server']['mapname']) . '
								</td>
								<td>' . esc_attr($s['data']['server']['bf2_mapsize']) . '</td>
								<td>' . (preg_match('/gpm_coop/i', $s['data']['server']['gametype']) ? 'Co-op' : 'Conquest') . '</td>
								<td>' . esc_attr($s['data']['server']['gamevariant']). '</td>
								<td>' . esc_attr(preg_replace('/^(\d+\.\d+).*$/', '$1', $s['data']['server']['gamever'])). '</td>
							</tr>';
								}
							}

							$template .= '
						</tbody>
						</table>
					</center>

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
