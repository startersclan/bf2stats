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
							
							<table cellspacing="0" cellpadding="0" border="0" style="width: auto;" class="stat">
							<tbody>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Ranked</th>
									<th>Punkbuster</th>
									<th>OS</th>
									<th>Autorecord</th>
									<th>VOIP</th>
									<th>Map	</th>
									<th>Game Mode</th>
									<th>Players</th>
									<th>Mod</th>
								</tr>';
								
								foreach($serversGamespyData as $k => $s) {	
									$template .= '
									<tr>
										<td>'.esc_attr($k).'</td>
										<td>'.esc_attr($s['server']['hostname']).'</td>
										<td>'.esc_attr($s['server']['bf2_ranked']).'</td>
										<td>'.esc_attr($s['server']['bf2_anticheat']).'</td>
										<td>'.esc_attr($s['server']['bf2_os']).'</td>
										<td>'.esc_attr($s['server']['bf2_autorec']).'</td>
										<td>'.esc_attr($s['server']['bf2_voip']).'</td>
										<td>'.esc_attr($s['server']['mapname']).'</td>
										<td>'.esc_attr($s['server']['gametype']).'</td>
										<td>'.esc_attr("{$s['server']['numplayers']} / {$s['server']['maxplayers']}").'</td>
										<td>'.esc_attr($s['server']['gamevariant']).'</td>
									</tr>';
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
