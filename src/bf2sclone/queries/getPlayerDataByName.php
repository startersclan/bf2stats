<?php
	$WHERE = '';
	if (RANKING_HIDE_BOTS) {
		$WHERE .= ' AND player.isbot = 0';
	}
	if (RANKING_HIDE_HIDDEN_PLAYERS) {
		$WHERE .= ' AND player.hidden = 0';
	}
	if (RANKING_HIDE_PIDS_START) {
		$WHERE .= ' AND player.id >= ' . RANKING_HIDE_PIDS_START;
	}
	if (RANKING_HIDE_PIDS_END) {
		$WHERE .= ' AND player.id <= ' . RANKING_HIDE_PIDS_END;
	}

	// Example when using a prefix, player name will be '<prefix> <nickname>'. 
	$query = "SELECT * FROM player WHERE (name = '$NICK' OR name like '% $NICK') $WHERE LIMIT 1;"; 
?>
