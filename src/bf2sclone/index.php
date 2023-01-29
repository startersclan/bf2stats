<?php
// Check if our config file exists... if not, we need to install
if(!file_exists('config.inc.php'))
{
	header("Location: install.php");
	die();
}

$host = rtrim($_SERVER['HTTP_HOST'], '/');
$site_dir = dirname( $_SERVER['PHP_SELF'] );
$site_url = str_replace('//', '/', $host .'/'. $site_dir);
while(strpos($site_url, '//') !== FALSE) $site_url = str_replace('//', '/', $site_url);

// Root url to bf2sclone
$ROOT = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) || ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ? str_replace( '\\', '', 'https://' . rtrim($site_url, '/') .'/' ) : str_replace( '\\', '', 'http://' . rtrim($site_url, '/') .'/' );

// Your domain name (eg: www.example.com)
$DOMAIN = preg_replace('@^(http(s)?)://@i', '', $host);

// cleanup
unset($host, $site_dir, $site_url);

// Include our config file
include('config.inc.php');

// Setup the database connection
$link = mysqli_connect($DBIP, $DBLOGIN, $DBPASSWORD) or die('Could not connect: ' . mysqli_error($GLOBALS['link']));
mysqli_select_db($GLOBALS['link'], $DBNAME) or die('Could not select database');

// process page start:
$time_start = microtime(true);

// Define a smaller Directory seperater and ROOT path
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('CACHE_PATH', ROOT . DS . 'cache' . DS);
define('TEMPLATE_PATH', ROOT . DS . 'template' . DS);

// IFF PID -> go show stats!
$PID = isset($_GET["pid"]) ? mysqli_real_escape_string($GLOBALS['link'], $_GET["pid"]) : "0";
$SID = isset($_GET["sid"]) ? mysqli_real_escape_string($GLOBALS['link'], $_GET["sid"]) : "0";
$GO = isset($_GET["go"]) ? $_GET["go"] : "0";
$GET = isset($_POST["get"]) ? $_POST["get"] : 0;
$SET = isset($_POST["set"]) ? $_POST["set"] : 0;
$ADD = isset($_GET["add"]) ? $_GET["add"] : 0;
$REMOVE = isset($_GET["remove"]) ? $_GET["remove"] : 0;
$LEADERBOARD = isset($_POST["leaderboard"]) ? $_POST["leaderboard"] : "0";

// Check for leaderboard getting / setting
if($SET)
{
	setcookie("leaderboard", $LEADERBOARD, time() + 315360000, '/', $DOMAIN); // delete after 10 years ;)
	#NOTE: after setting a cookie, you must redirect!
	header("Location: ".$ROOT."?go=my-leaderboard"); // refresh for cookie
	exit();
}

if($GET)
{
	// output the nice save-url
	header("Location: ". $ROOT .'?go=my-leaderboard&pid='.urlencode($LEADERBOARD));
	exit();
}

/* IMPLEMENTED FUNCTIONS */
include( ROOT . DS . 'functions.inc.php' );

/* PLAYER STATS SQL FUNCTIONS*/
include( ROOT . DS . 'playerstats.inc.php' );
include( ROOT . DS . 'awards.inc.php' );
include( ROOT . DS . 'expansions.inc.php' );

/* RANKING STATS SQL FUNCTIONS*/
include( ROOT . DS . 'rankingstats.inc.php' );

/* SEARCH SQL FUNCTIONS*/
include( ROOT . DS . 'search.inc.php' );

/* SERVERS SQL FUNCTIONS*/
include( ROOT . DS . 'servers.inc.php' );

/* LEADERBOARD AND HOME (as home includes leaderboard) */
include( ROOT . DS . 'leaderboard.inc.php' );

header('Cache-Control: private, no-cache, no-store, must-revalidate, s-maxage=0, max-age=0');

/***************************************************************
 * PLAYERSTATS
 ***************************************************************/
if($GO == "0" && $PID)
{
	#$awards = getAwardsByPID($PID); // get earned awards
	if(isCached($PID))// already cached!
	{
		$template 	= getCache($PID);
		$LASTUPDATE = intToTime( getLastUpdate( CACHE_PATH . $PID .'.cache') );
		$NEXTUPDATE = intToTime( getNextUpdate( CACHE_PATH . $PID .'.cache', RANKING_REFRESH_TIME) );
		$template 	= str_replace('{:LASTUPDATE:}', $LASTUPDATE, $template);
		$template 	= str_replace('{:NEXTUPDATE:}', $NEXTUPDATE, $template);
	}
	else
	{
		// Load Player Data
		$player 		= getPlayerDataFromPID($PID); // receive player data
		if (!$player) {
			header("Location: /"); // Redirect to home, since there's no such player
			exit();
		}
		$victims 		= getFavouriteVictims($PID); // receive victim data
		$enemies 		= getFavouriteEnemies($PID); // receive enemie data
		$armies 		= getArmyData($PID); // receive army data
		$armySummary 	= getArmySummaries($armies); // retrieve Army summary
		$unlocks 		= getUnlocksByPID($PID);	// retrieve unlock data
		$vehicles 		= getVehicleData($PID);	// retrieve vehivle data
		$vehicleSummary = getVehicleSummaries($vehicles); // retrieve Vehicle summary
		$weapons 		= getWeaponData($PID, $player); // retrieve Weapon data
		$weaponSummary 	= getWeaponSummary($weapons, $player); // retrieve weapon summary
		$equipmentSummary = getEquipmentSummary($weapons, $player); // retrieve equipment summary
		$kits 			= getKitData($PID); // retrieve kit data
		$kitSummary 	= getKitSummary($kits, $player); // retrieve kits summary
		$maps 			= getMapData($PID);
		$mapSummary 	= getMapSummary($maps);
		$TheaterData 	= getTheaterData($PID);  // retrueve Theater Data
		$playerFavorite = getPlayerFavorites($weapons, $vehicles, $kits, $armies, $maps, $TheaterData); // get player summary
		$PlayerAwards  	= getAwardsByPID($PID);

		// Include our template file
		include( TEMPLATE_PATH . 'playerstats.php' );

		// write cache file
		writeCache($PID, trim($template));
		$LASTUPDATE = intToTime(0);
		$NEXTUPDATE = intToTime(RANKING_REFRESH_TIME);
		$template 	= str_replace('{:LASTUPDATE:}', $LASTUPDATE, $template);
		$template 	= str_replace('{:NEXTUPDATE:}', $NEXTUPDATE, $template);
	}
}

/***************************************************************
 * CURRENT RANKINGS
 ***************************************************************/
elseif(strcasecmp($GO, 'currentranking') == 0)
{
	$rankings = getRankingCollection();
	$LASTUPDATE = 0;
	$NEXTUPDATE = 0;
	if(isCached('current-ranking'))// already cached!
	{
		$template 	= getCache('current-ranking');
		$LASTUPDATE = intToTime(getLastUpdate( CACHE_PATH . 'current-ranking.cache'));
		$NEXTUPDATE = intToTime(getNextUpdate( CACHE_PATH . 'current-ranking.cache', RANKING_REFRESH_TIME));
	}
	else
	{
		// Include our template file
		include( TEMPLATE_PATH .'current-ranking.php');

		// write cache file
		writeCache('current-ranking', $template);
		$LASTUPDATE = intToTime(0);
		$NEXTUPDATE = intToTime(RANKING_REFRESH_TIME);
	}
	$template = str_replace('{:LASTUPDATE:}', $LASTUPDATE, $template);
	$template = str_replace('{:NEXTUPDATE:}', $NEXTUPDATE, $template);
	#echo $template;
}

/***************************************************************
 * MY LEADER BOARD
 ***************************************************************/
elseif(strcasecmp($GO, 'my-leaderboard') == 0)
{
	if($ADD > 0)
	{
		if ($_COOKIE['leaderboard'] != '')
		{
			$LEADERBOARD = $_COOKIE['leaderboard'].','.$ADD;
		}
		else
		{
			$LEADERBOARD = $ADD;
		}
		setcookie("leaderboard", $LEADERBOARD, time()+315360000, '/', $DOMAIN); // delete after 10 years ;)
		#NOTE: after setting a cookie, you must redirect!
		header("Location: ".$ROOT."?go=my-leaderboard"); // refresh for cookie
		exit();
	}
	elseif($REMOVE > 0)
	{
		$LEADERBOARD = explode(',', $_COOKIE['leaderboard']); // get array

		// delete "remove"
		foreach($LEADERBOARD as $i => $value)
		{
			if($value == $REMOVE)
			{
				unset($LEADERBOARD[$i]);
			}
		}
		$LEADERBOARD = implode(',', $LEADERBOARD); // back to string ;)

		setcookie("leaderboard", $LEADERBOARD, time() + 315360000, '/', $DOMAIN); // delete after 10 years ;)
		header("Location: ".$ROOT."?go=my-leaderboard"); // refresh for cookie
		exit();
	}
	# nothing todo -> load from cookie
	$LEADERBOARD = isset($_COOKIE['leaderboard']) ? $_COOKIE['leaderboard'] : '';

	if($PID != 0) // a saved leaderboard
	{
		$LEADER = getLeaderBoardEntries(urldecode($PID)); # query from database
	}
	else
	{
		$LEADER = getLeaderBoardEntries($LEADERBOARD); # query from database
	}

	// Include our template file
	include( TEMPLATE_PATH .'my-leaderboard.php');
}

/***************************************************************
 * SEARCH FOR PLAYERS
 ***************************************************************/
elseif(strcasecmp($GO, 'search') == 0)
{
	$SEARCHVALUE = isset($_POST["searchvalue"]) ? $_POST["searchvalue"] : "0";
	if($SEARCHVALUE) $searchresults = getSearchResults($SEARCHVALUE);
	include( TEMPLATE_PATH .'search.php');
}

/***************************************************************
 * SERVERS
 ***************************************************************/
elseif(strcasecmp($GO, 'servers') == 0 && !$SID)
{
	$LASTUPDATE = 0;
	$NEXTUPDATE = 0;
	if(isCached('servers'))// already cached!
	{
		$template 	= getCache('servers');
		$LASTUPDATE = intToTime(getLastUpdate( CACHE_PATH . 'servers.cache'));
		$NEXTUPDATE = intToTime(getNextUpdate( CACHE_PATH . 'servers.cache', RANKING_REFRESH_TIME));
	}
	else
	{
		$servers = getServers();
		$serversWithGamespyData = array();
		foreach ($servers as $s) {
			$s['data'] = loadGamespyData($s['ip'], $s['queryport']);
			$serversWithGamespyData[] = $s;
		}
		$servers = $serversWithGamespyData; 
		unset($serversWithGamespyData);

		// Include our template file
		include( TEMPLATE_PATH .'servers.php');

		// write cache file
		writeCache('servers', $template);
		$LASTUPDATE = intToTime(0);
		$NEXTUPDATE = intToTime(RANKING_REFRESH_TIME);
	}
	$template = str_replace('{:LASTUPDATE:}', $LASTUPDATE, $template);
	$template = str_replace('{:NEXTUPDATE:}', $NEXTUPDATE, $template);
	#echo $template;
}

/***************************************************************
 * SERVER
 ***************************************************************/
elseif(strcasecmp($GO, 'servers') == 0 && $SID)
{

	$LASTUPDATE = 0;
	$NEXTUPDATE = 0;
	if(isCached('server'))// already cached!
	{
		$template 	= getCache('server');
		$LASTUPDATE = intToTime(getLastUpdate( CACHE_PATH . 'server.cache'));
		$NEXTUPDATE = intToTime(getNextUpdate( CACHE_PATH . 'server.cache', RANKING_REFRESH_TIME));
	}
	else
	{
		$server = getServer($SID);
		$server['data'] = loadGamespyData($server['ip'], $server['queryport']);
		$server['data'] = getGamespyDataWithPlayerRanks($server['data']);

		// Include our template file
		include( TEMPLATE_PATH .'server.php');

		// write cache file
		writeCache('server', $template);
		$LASTUPDATE = intToTime(0);
		$NEXTUPDATE = intToTime(RANKING_REFRESH_TIME);
	}
	$template = str_replace('{:LASTUPDATE:}', $LASTUPDATE, $template);
	$template = str_replace('{:NEXTUPDATE:}', $NEXTUPDATE, $template);
	#echo $template;
}

/***************************************************************
 * UBAR PAGES
 ***************************************************************/
elseif(strcasecmp($GO, 'ubar') == 0)
{
	// Make sure we have a sub page
	$page = (isset($_GET['p'])) ? $_GET['p'] : 'index';
	switch($page)
	{
		default:
		case "index":
			$page = 'ubar-index';
			break;

		case "ribbons":
		case "ribbons-sf":
		case "medals":
		case "medals-sf":
		case "badges":
		case "badges-sf":
		case "ranks":
			$page = 'ubar-'. $page;
			break;
	}

	// Include our template file
	include( TEMPLATE_PATH . $page .'.php');
	#echo $template;
}

/***************************************************************
 * SHOW TOP TEN - default
 ***************************************************************/
else
{  // show the top ten

	$LASTUPDATE = 0;
	$NEXTUPDATE = 0;
	if(isCached('home'))// already cached!
	{
		$template = getCache('home');
		$LASTUPDATE = intToTime(getLastUpdate( CACHE_PATH .'home.cache' ));
		$NEXTUPDATE = intToTime(getNextUpdate( CACHE_PATH .'home.cache', RANKING_REFRESH_TIME ));
	}
	else
	{
		$leaders = getTopPlayers();
		include( TEMPLATE_PATH .'home.php');

		// write cache file
		writeCache('home', $template);
		$LASTUPDATE = intToTime(0);
		$NEXTUPDATE = intToTime(RANKING_REFRESH_TIME);
	}
	$template = str_replace('{:LASTUPDATE:}', $LASTUPDATE, $template);
	$template = str_replace('{:NEXTUPDATE:}', $NEXTUPDATE, $template);

}

// Closing connection
mysqli_close($GLOBALS['link']);

//processing page END
$time_end = microtime(true);
$time = round($time_end - $time_start,4);

$template = str_replace('{:PROCESSED:}', $time, $template);

// Echo the template page and quit
echo $template;
?>
