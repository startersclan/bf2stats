<?php
function getVar($name, $default) {
	$value = getenv($name) !== false ? getenv($name) : $default;
	$value = gettype($default) == 'boolean' && $value == 'false' ? false : $value; // Fix string 'false' becoming true for boolean when using settype
	settype($value, gettype($default));
    return $value;
}
function defineVar($name, $default) {
	$value = getenv($name) !== false ? getenv($name) : $default;
	$value = gettype($default) == 'boolean' && $value == 'false' ? false : $value; // Fix string 'false' becoming true for boolean when using settype
	settype($value, gettype($default));
	define($name, $value);
}

// Database connection information
$DBIP = getVar('DBIP', '127.0.0.1');
$DBNAME = getVar('DBNAME', 'bf2stats');
$DBLOGIN = getVar('DBLOGIN', 'admin');
$DBPASSWORD = getVar('DBPASSWORD', 'admin');

// Leader board title
$TITLE = getVar('TITLE', 'BF2S Clone');

// Refresh time in seconds for stats
defineVar('RANKING_REFRESH_TIME', 600); // -> default: 600 seconds (10 minutes)

// Whether to hide bots from rankings
defineVar('RANKING_HIDE_BOTS', false);

// Whether to hide hidden players from rankings
defineVar('RANKING_HIDE_HIDDEN_PLAYERS', false);

// Number of players to show on the leaderboard frontpage
defineVar('LEADERBOARD_COUNT', 25);
?>
