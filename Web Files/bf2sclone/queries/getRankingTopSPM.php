<?php
	$query = "SELECT id,name,rank,score/(time/60) as spm,country FROM player WHERE 1=1 ORDER BY spm DESC LIMIT 5;";
?>