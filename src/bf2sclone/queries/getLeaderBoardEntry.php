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
	
	$query = 'SELECT id,name,kills,rank,score,(score/(time/60)) as spm, (kills/deaths) as kdr, time, country FROM player WHERE (';
	if ($LEADERBOARD)
	{
		$first = true;
		foreach (explode(',', $LEADERBOARD) as $key => $value)
		{
			if ($first)
			{
				$query .= " id='$value'";
				$first = false;
			}
			else
				$query .= "or id='$value'";
		}
		$query .= ") $WHERE ORDER BY SCORE DESC LIMIT 50;";
	}
	else
		$query = "SELECT id,name,rank,kills,score,(score/(time/60)) as spm, (kills/deaths) as kdr, time, country FROM player WHERE score > 0 $WHERE ORDER BY SCORE DESC LIMIT 10;";
?>
