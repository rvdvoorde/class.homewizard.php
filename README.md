class.homewizard.php
=======

This is a simple php class to get the communications going with a homewizard using php.
You can use this class to controll switches/dimmers/somfy and read all the data from the homewizard.

A few useage examples:

<code>
<?php

session_start();

//Load the homewizard class...
require_once('class.homewizard.php');
$hw = new homewizard();

$hw->ip_address = '192.168.1.5';
$hw->password = 'PASSWORD';

//or...

if (!isset($_SESSION['hw_ip_address'])) {
	if (!$hw->locate_hw()) {
		echo '<b>no homewizard detected!</b>';
		exit;
	}
} else {
	$hw->ip_address = $_SESSION['hw_ip_address'];
}


//Load the sunrise/sunset times for today and displey them
if ($hw->suntimes()) {
	echo "<br/>\n";
	echo 'Sun up: '.$hw->sunrise." - ";
	echo 'Sun down: '.$hw->sunset."<br/>\n";
}

//Load all the switches
$hw->get_switches();

echo '<ul>';
foreach ($hw->switches as $value) {
	if ($value->dimmer == 'yes') {
		echo '<li>'.$value->name.' ['.$hw->replace_text($value->status).'] { <a href="?id='.$value->id.'&value=7"><button>Schakel in</button></a> <a href="?id='.$value->id.'&value=0"><button>Schakel uit</button></a>'." } Dimmer: ".$value->dimlevel."% ]</li>\n";
	} else {
		echo '<li>'.$value->name.' ['.$hw->replace_text($value->status).'] { <a href="?id='.$value->id.'&value=7"><button>Schakel in</button></a> <a href="?id='.$value->id.'&value=0"><button>Schakel uit</button></a>'." } ]</li>\n";
	}
}
echo '</ul>';

//etc...
?>
</code>
