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

	#NOTE: minimum 1 death
	$query = "SELECT id,name,rank, kills/deaths as kdr,country FROM player WHERE 1=1 $WHERE ORDER BY kdr DESC LIMIT 5;";
?>
