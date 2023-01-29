<?php

function getServer($id)
{
	include(ROOT . DS . 'queries'. DS .'getServerByID.php'); // imports the correct sql statement
	$result = mysqli_query($GLOBALS['link'], $query) or die('Query failed: ' . mysqli_error($GLOBALS['link']));
	$server = mysqli_fetch_assoc($result);
	return $server;
}

function getServers()
{
	include(ROOT . DS . 'queries'. DS .'getServers.php'); // imports the correct sql statement
	$result = mysqli_query($GLOBALS['link'], $query) or die('Query failed: ' . mysqli_error($GLOBALS['link']));
	$servers = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$servers[] = $row;
	}
	return $servers;
}

function loadGamespyData($ip, $port)
{
	// Setup our predefined vars
	$i = 1;
	$end = false;
	$Packet = array(1 => '', 2=> '', 3 => '');

	// Open our socket to the server, UDP port always open so we cant determine
	// the online status of our server yet!
	error_log('[0] ' . microtime());
	$sock = @fsockopen("udp://". $ip, $port);
	@socket_set_timeout($sock, 0, 500000);

	// Query the gamespy data
	$queryString = "\xFE\xFD\x00\x10\x20\x30\x40\xFF\xFF\xFF\x01";
	@fwrite($sock, $queryString);

	// Look through and read each of the 3 packets that get returned
	while(!$end) 
	{
		error_log("[1a] i: $i, microtime: " . microtime());
		$bytes = @fread($sock, 1);
		error_log("[1b] i: $i, microtime: " . microtime());
		$status = @socket_get_status($sock);
		$length = $status['unread_bytes'];
		error_log("[1c] i: $i, microtime: " . microtime(). ', length: ' . $length);
		if($length > 0)
		{
			$Info[$i] = $bytes . fread($sock, $length);
			$status = @socket_get_status($sock);
	
			error_log("[1d] i: $i, microtime: " . microtime(). ', length: ' . $status['unread_bytes']);

			preg_match("/splitnum(...)/is",$Info[$i],$regs);
			$String = $regs[1];

			$num = ord(substr($String,1,1));

			if($num == 128 || $num == 0) 
			{
				$Packet[1] = $Info[$i];
			}

			if ($num == 129 || $num == 1) 
			{
				$Packet[2] = $Info[$i];
			}

			if ($num == 130) 
			{
				$Packet[3] = $Info[$i];
			}
		}

		if($length == 0) 
		{
			$end = true;
		}
		
		// Smaller servers will respond with only 2 packets.
		// If we got all 2 packets, and Packet 2 starts with this header, we're done. Don't need to wait for socket timeout
		if ($i == 2 && stripos($Packet[2], "\x00\x10\x20\x30@splitnum\x00\x81\x01") === 0) {
			$end = true;
		}

		// Larger servers will respond with 3 packets.
		// If we got all 3 packets, and Packet 3 starts with this header, we're done. Don't need to wait for socket timeout
		if ($i == 3 && stripos($Packet[3], "\x00\x10\x20\x30@splitnum\x00\x82\x02") === 0) {
			$end = true;
		}

		$i++;

	}

	// Close the socket and build our packet string
	@fclose($sock);
	error_log("[2] i: $i, microtime: " . microtime());
	$Info = $Packet[1] . $Packet[2] . $Packet[3];
	
	// If our string is empty, return false
	if(empty($Info)) return FALSE;
	
	// Parse our returned packets
	$output = str_replace("\\","",$Info);
	$changeChr = chr(0);
	$output = str_replace($changeChr, "\\", $output);
	$rules = "x".substr($output,0,strpos($output,"\\\\".chr(1)));
	$players = "\\".substr($output,strpos($output,"\\\\".chr(1))+3);
	$p3 = strpos($players,"\\\\".chr(2));

	if(!$p3) 
	{
		$p3 = strpos($players,"\\\\team_t");
	}
	if(!$p3) 
	{
		$p3 = strpos($players,"\�team_t");
	}

	// Parse players
	$players = $p3 ? substr($players,0,$p3) : substr($players,0);
	$players = str_replace("\\ 0@splitnum\�","",$players);
	$players = str_replace("\\ 0@splitnum\\�","",$players);
	$players = str_replace(" 0@splitnum\\","",$players);
	$players = str_replace(" 0@splitnum\\�","",$players);
	$players = str_replace("\x10\x20\x30@splitnum\\\x81\x01","",$players);
	$players = str_replace("\x10\x20\x30@splitnum\\\x82\x02","",$players);

	//Parse Rules
	$rule_temp = substr($rules,1);
	$rule_temp = str_replace("�","\\",$rule_temp);
	$rules_arr = explode("\\",$rule_temp);
	$rules_count = count($rules_arr);

	// Build our server data into a nice array
	for($i=0; $i < ($rules_count / 2); $i++) 
	{
		$r1[$i] = $rules_arr[$i*2];
		$r2[$i] = $rules_arr[($i*2)+1];
		$rule[$r1[$i]] = $r2[$i];

	}

	$tags = explode("\\",$players);

	$index = 0;
	$player = array();
	$currentProp = "";
	$newIndexFlag = false;
	$propCount = 0;
	$tagCount = count($tags) -1;

	for($i = 0; $i < $tagCount; $i++) 
	{
		if($tags[$i] == "" && substr($tags[$i+1], strlen($tags[$i+1]) -1, 1) == "_" && $tags[$i+1] != $currentProp && ord($tags[$i+2]) == 0) 
		{
			$currentProp = $tags[$i+1];
			$index = 0;
			$prop[$propCount] = $currentProp;
			$propCount++;
		} 
		else 
		{

			if($tags[$i] == $currentProp && ord($tags[$i+1]) != 0) 
			{
				$index = ord($tags[$i+1]);
				$newIndexFlag = true;
			} 
			else 
			{
				if($tags[$i]!="" && $currentProp!="" && $tags[$i]!=$currentProp) 
				{
					$player[$currentProp][$index] = $tags[$i];
					if($newIndexFlag) 
					{
						$player[$currentProp][$index] = substr($tags[$i],1);
						$newIndexFlag = false;
					}
					$index++;
				}
			}
		}
	}
	
	// Build out player list
	$data = array();
	$count = count($player['player_']);
	for ($p = 0; $p < $count; $p++) 
	{
		// Fix missing deaths bug in custom maps ??
		if(!isset($player["deaths_"][$p])) $player["deaths_"][$p] = 0;
		$data[] = array(
			'name' => $player["player_"][$p], 
			'score' => $player["score_"][$p],
			'kills' => $player["skill_"][$p],            
			'deaths' => $player["deaths_"][$p], 
			'ping' => $player["ping_"][$p], 
			'team' => $player["team_"][$p], 
			'pid' => $player["pid_"][$p],
			'ai' => $player["AIBot_"][$p]
		);
	}
	
	// Prepate our return array
	$return = array(
		'server' => $rule,
		'team1' => array(), 
		'team2' => array()
	);
	
	// Sort each player by team
	foreach($data as $player)
	{
		$return['team'. $player['team']][] = $player;
	}
	
	return $return;
}
	
function getArmyName($name)
{
	switch(strtolower($name)) 
	{
		case "mec":
			return "Middle Eastern Coalition";
			break;

		case "us":
		case "usa":
			return "United States Marine Corps";
			break;
		
		case "ch":
			return "People's Liberation Army";
			break;

		case "seal":
			return "Seals";
			break;

		case "sas":
			return "SAS";
			break;

		case "spetz":
			return "Spetsnaz";
			break;

		case "mecsf":
			return "Middle Eastern Coalition SF";
			break;

		case "chinsurgent":
		case "rebels":
			return "Rebels";
			break;

		case "meinsurgent":
		case "insurgents":
			return "Insurgents";
			break;

		case "eu":
			return "European Union";
			break;
			
		default:
			return "Unknown Army ($name)";
			break;
	}
}

function getGamespyDataWithPlayerRanks($gamespyData) {
	foreach (array('team1', 'team2') as $t) {
		$team = array();
		foreach ($gamespyData[$t] as $k => $p) {
			$NICK = $p['name'];
			include(ROOT . DS . 'queries'. DS .'getPlayerDataByName.php'); // imports the correct sql statement
			$result = mysqli_query($GLOBALS['link'], $query);
			$player = mysqli_fetch_assoc($result);
			if ($player) {
				// If it's a bot with id of '0', replace with its id in the DB
				if ($p['pid'] == 0) {
					$p['pid'] = $player['id'];
					$p['rank'] = $player['rank'];
					$team[] = $p;
				}
			}else {
				// The bot is not yet in the DB. 
				$p['pid'] = 0;
				$p['rank'] = 0;
				$team[] = $p;
			}
		}
		if (count($team) > 0) {
			$gamespyData[$t] = $team;
		}
	}
	return $gamespyData;
}

?>
