<?php

function intToTime($sec, $padHours = true, $padMins = true, $padSecs = true) 
{
	// start with a blank string
	$hms = "";

	// do the hours first: there are 3600 seconds in an hour, so if we divide
	// the total number of seconds by 3600 and throw away the remainder, we're
	// left with the number of hours in those seconds
	$hours = intval(intval($sec) / 3600); 

	// add hours to $hms (with a leading 0 if asked for)
	$hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":" : $hours. ":";

	// dividing the total seconds by 60 will give us the number of minutes
	// in total, but we're interested in *minutes past the hour* and to get
	// this, we have to divide by 60 again and then use the remainder
	$minutes = intval(($sec / 60) % 60); 

	// add minutes to $hms (with a leading 0 if needed)
	$hms .= ($padMins) ? str_pad($minutes, 2, "0", STR_PAD_LEFT). ":" : $minutes. ":";

	// seconds past the minute are found by dividing the total number of seconds
	// by 60 and using the remainder
	$seconds = intval($sec % 60); 

	// add seconds to $hms (with a leading 0 if needed)
	$hms .= ($padSecs) ? str_pad($seconds, 2, "0", STR_PAD_LEFT) : $seconds;

	// done!
	return $hms;

}

function intToMins($time)
{
	$secs = round($time%60, 0);
	return $mins = round($time/60, 0);
}

/* CHACHING FUNCTIONS */

function isCached($id)
{
	$file = CACHE_PATH . $id .'.cache';
	if(getNextUpdate($file, RANKING_REFRESH_TIME) > 0)
	{
		return true;
	}
	else
	{
		deleteCache($id);
		return false;
	}
}

function getCache($id)
{
	$file = CACHE_PATH . $id .'.cache';
	if(file_exists($file))
	{
		return file_get_contents($file);
	}
	else
	{
		return false;
	}		
}

function writeCache($id, $content)
{
	//write the file
	file_put_contents( CACHE_PATH . $id .'.cache', $content );
}

function deleteCache($id)
{
	$file = CACHE_PATH . $id .'.cache';
	if (file_exists($file))
	{
		unlink($file);
	}
}

function cleanCache()
{
	$files = dirList( CACHE_PATH ) ;
	foreach ($files as $key => $value)
	{
		#echo "$value - last update:". intToTime(getLastUpdate($value)).' -- next update in '.intToTime(getNextUpdate($value)).'<br>';
		if (stripos($value,'current-ranking.cache') || stripos($value,'home.cache'))
		{
			// this is the ranking -> update this more often! 600 = every 10 minutes
			if (getNextUpdate($value, RANKING_REFRESH_TIME) < -1) // file is out of date -> delete (will be renewed when next user requests...)
				unlink($value); // file deleted!
		}
		else 
			if (getNextUpdate($value) < -1) // file is out of date -> delete (will be renewed when next user requests...)
				unlink($value); // file deleted!		
	}
}

function deleteCompleteCache()
{
	$files = dirList( CACHE_PATH ) ;
	foreach($files as $key => $value)
	{
		unlink($value); // file deleted!
	}
}

function getNextUpdate($filename, $limit=3600)
{
	if(file_exists($filename))
	{
		return (fileatime($filename)+$limit)-time();
	}
	else
	{
		return -1; // file not found 
	}		
}


function getLastUpdate($filename)
{
	if(file_exists($filename))
	{
		return time()-fileatime($filename);
	}
	else
	{
		return -1; // file not found
	}
}

function dirList($directory) 
{
    // create an array to hold directory list
    $results = array();
    // create a handler for the directory
    $handler = opendir($directory);
    // keep going until all files in directory have been read
    while ($file = readdir($handler)) {
        // if $file isn't this directory or its parent, 
        // add it to the results array
        if ($file != '.' && $file != '..')
            $results[] = $directory.$file;
    }
    // tidy up: close the handler
    closedir($handler);
    // done!
    return $results;
}


function getRatio($val1, $val2)
{
	if($val2)
		return ($val1 / $val2);
	else
		return $val1;
}

function getPercent($val1, $val2)
{
	if($val1)
		return (100 * $val2 / $val1);
	else
		return 0;
}

function esc_attr($val) {
	return htmlspecialchars($val, ENT_QUOTES);
}

?>
