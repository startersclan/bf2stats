<?php
$template = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="inner">
<head>
	<title>Servers, '. $TITLE .'</title>

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
	
		<h1 id="page-title">All Servers</h1>
		<div id="page-3">
			<div id="content"><div id="content-id">
	
				<!--
				<ul id="stats-nav">
					<li class="current"><a href="'.$ROOT.'">Home</a></li>
					<li><a href="'.$ROOT.'?go=search">Search Stats</a></li>
					<li><a href="'.$ROOT.'?go=currentranking">Current Ranking</a></li>
					<li><a href="'.$ROOT.'?go=my-leaderboard">My Leaderboard</a></li>
				</ul>
				-->
			
				<div id="content">
					<div id="content-id"><!-- template header end == begin content below -->
						<center>
							<h2>Servers</h2>
							
							<table cellspacing="0" cellpadding="0" border="0" style="width: auto;" class="stat servers">
							<tbody>
								<tr>
									<th></th>
									<th>SERVER NAME</th>
									<th>PLAYERS</th>
									<th>MAP NAME</th>
									<th>GAME MODE</th>
									<th>GAME MOD</th>
									<th>VERSION</th>
								</tr>';
								
								foreach($serversGamespyData as $k => $s) {	
									$template .= '
									<tr>
										<td>';
										
									$serverLoad = $s['server']['numplayers'] / $s['server']['maxplayers'];
									if ($serverLoad >= 0.66) {
										$template .= '<img src="game-images/serverIcons/Serverload_red.png" alt="Serverload_red" />';
									} else if ($serverLoad >= 0.33) {
										$template .= '<img src="game-images/serverIcons/Serverload_orange.png" alt="Serverload_orange" />';
									} else {
										$template .= '<img src="game-images/serverIcons/Serverload_green.png" alt="Serverload_green" />';
									}

									if (preg_match('/^linux/', $s['server']['bf2_os'])) {
										$template .= '<img src="game-images/serverIcons/linux.png" alt="linux" />';
									} else {
										$template .= '<img src="game-images/serverIcons/Windows.png" alt="Windows" />';
									}

									if (preg_match('/-64$/', $s['server']['bf2_os'])) {
										$template .= '<img src="game-images/serverIcons/64bit.png" alt="linux" />';
									} else {
										$template .= '<img src="game-images/serverIcons/Windows.png" alt="Windows" />';
									}
									
									if (file_exists(ROOT . "/game-images/mods/{$s['server']['gamevariant']}/mod_icon.png")) {
										$mod = esc_attr($s['server']['gamevariant']);
										$template .= "<img src=\"game-images/mods/$mod/mod_icon.png\" alt=\"$mod\" />";
									} else {
										$template .= '<img src="game-images/serverIcons/unknown_mod.png" alt="unknown_mod" />';
									}

									if ($s['server']['bf2_ranked']) {
										$template .= '<img src="game-images/serverIcons/Ranked.png" alt="Ranked" />';
									} else {
										$template .= '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="Unranked" />';
									}

									if ($s['server']['bf2_autorec']) {
										$template .= '<img src="game-images/serverIcons/battlerec.png" alt="battlerec" />';
									} else {
										$template .= '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="BattleRecorder Off" />';
									}

									if ($s['server']['bf2_voip']) {
										$template .= '<img src="game-images/serverIcons/battleCom.png" alt="battleCom" />';
									} else {
										$template .= '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="BattleCommo Off" />';
									}

									if ($s['server']['bf2_anticheat']) {
										$template .= '<img src="game-images/serverIcons/punkBuster.png" alt="punkBuster" />';
									} else {
										$template .= '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="No Punkbuster" />';
									}
									
									if ($s['server']['bf2_pure']) {
										$template .= '<img src="game-images/serverIcons/PureContent.png" alt="PureContent" />';
									} else {
										$template .= '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="Modded" />';
									}
									
									if ($s['server']['password']) {
										$template .= '<img src="game-images/serverIcons/PasswordEnabled.png" alt="PasswordEnabled" />';
									} else {
										$template .= '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="No Password" />';
									}

										// <img src="game-images/serverIcons/64bit.png" alt="64bit" />
										// <img src="game-images/serverIcons/battleCom.png" alt="battleCom" />
										// <img src="game-images/serverIcons/battlerec.png" alt="battlerec" />
										// <img src="game-images/serverIcons/Battlerecord.png" alt="Battlerecord" />
										// <img src="game-images/serverIcons/iconEmpty.png" alt="iconEmpty" />
										// <img src="game-images/serverIcons/linux.png" alt="linux" />
										// <img src="game-images/serverIcons/modNotInstalled.png" alt="modNotInstalled" />
										// <img src="game-images/serverIcons/PasswordEnabled.png" alt="PasswordEnabled" />
										// <img src="game-images/serverIcons/punkBuster.png" alt="punkBuster" />
										// <img src="game-images/serverIcons/PureContent.png" alt="PureContent" />
										// <img src="game-images/serverIcons/Ranked.png" alt="Ranked" />
										// <img src="game-images/serverIcons/Serverload_green.png" alt="Serverload_green" />
										// <img src="game-images/serverIcons/Serverload_orange.png" alt="Serverload_orange" />
										// <img src="game-images/serverIcons/Serverload_red.png" alt="Serverload_red" />
										// <img src="game-images/serverIcons/unknown_mod.png" alt="unknown_mod" />
										// <img src="game-images/serverIcons/Windows.png" alt="Windows" />
										// </td>
									$template .= '
										</td>' .
										'<td>' . esc_attr($s['server']['hostname']) . '</td>' .
										'<td>' . esc_attr("{$s['server']['numplayers']}/{$s['server']['maxplayers']}"). '</td>' .
										'<td>' . esc_attr($s['server']['mapname']). '</td>' .
										'<td>' . (preg_match('/gpm_coop/i', $s['server']['gametype']) ? 'Co-op' : 'Conquest') . '</td>' .
										'<td>' . esc_attr($s['server']['gamevariant']). '</td>' .
										'<td>' . esc_attr(preg_replace('/^(\d+\.\d+).*$/', '$1', $s['server']['gamever'])). '</td>' .
									'</tr>';
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
			</div></div>
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
