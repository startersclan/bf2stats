<?php
$template = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="inner">
<head>
	<title>Rankings, '. $TITLE .'</title>
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
		<h1 id="page-title">Current Rankings <small>"nuh uh! I\'m better!"</small></h1>
		<div id="page-3">
			<div id="content"><div id="content-id"><!-- template header end == begin content below -->
		
				<!--
				<ul id="stats-nav">
					<li><a href="'.$ROOT.'">Home</a></li>
					<li><a href="'.$ROOT.'?go=search">Search Stats</a></li>
					<li class="current"><a href="'.$ROOT.'?go=currentranking">Current Ranking</a></li>
					<li><a href="'.$ROOT.'?go=my-leaderboard">My Leaderboard</a></li>
				</ul>
				-->
				
				<table id="rankslist-home" class="stat" border="0" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<th>Stat</th>
						<th>#1</th>
						<th>#2</th>
						<th>#3</th>
						<th>#4</th>
						<th>#5</th>
					</tr>';
				
					foreach ($rankings as $key => $value)
					{
						$desc = (isset($value['desc']) && !empty($value['desc'])) ? $value['desc'] : '&nbsp;';
						$template .= '
						<tr>
							<td>'.$value['name'].'</td>';
						
						foreach($value['data'] as $v)
						{
							$template .= '
							<td>
								<img src="'.$ROOT.'game-images/ranks/icon/rank_'.$v['rank'].'.gif" alt="'.getRankByID($v['rank']).'">
								<a href="'.$ROOT.'?pid='.$v['id'].'">'.$v['name'].'</a>
								<br/>'. $v['value'] .'
							</td>';
						}
						$template .= '
						</tr>		
						<tr class="separator">
							<td colspan="6">'. $desc .'</td>
						</tr>';
					}
				
				$template .= '	
				</tbody>
				</table>

				<p><!-- end content == footer below --></p>
				<p>&nbsp;</p>
				<p>&nbsp; </p>
				<hr class="clear">
	
			</div></div> <!-- content-id --><!-- content -->
			<a id="secondhome" href="'.$ROOT.'"> </a>

		</div><!-- page 3 -->	
	</div><!-- page 2 -->
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
</div><!-- page 1 -->
</body>
</html>';
