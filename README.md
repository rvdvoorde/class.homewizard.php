class.homewizard.php
=======

This is a simple php class to get the communications going with a homewizard using php.
You can use this class to controll switches/dimmers/somfy and read all the data from the homewizard.

A few useage examples:

<code>
<?php

session_start();

//Load the homewizard class...<br/>
require_once('class.homewizard.php');<br/>
$hw = new homewizard();<br/><br/>

$hw->ip_address = '192.168.1.5';<br/>
$hw->password = 'PASSWORD';<br/>

//Load the sunrise/sunset times for today and displey them`<br/>
if ($hw->suntimes()) {<br/>
	echo 'Sun up: '.$hw->sunrise." - ";<br/>
	echo 'Sun down: '.$hw->sunset';<br/>
}<br/>

//Load all the switches<br/>
$hw->get_switches();<br/>

echo '<ul>';<br/>
foreach ($hw->switches as $value) {<br/>
	if ($value->dimmer == 'yes') {<br/>
		echo '<li>'.$value->name.' ['.$hw->replace_text($value->status).'] { <a href="?id='.$value->id.'&value=7"><button>Schakel in</button></a> <a href="?id='.$value->id.'&value=0"><button>Schakel uit</button></a>'." } Dimmer: ".$value->dimlevel."% ]</li>\n";<br/>
	} else {<br/>
		echo '<li>'.$value->name.' ['.$hw->replace_text($value->status).'] { <a href="?id='.$value->id.'&value=7"><button>Schakel in</button></a> <a href="?id='.$value->id.'&value=0"><button>Schakel uit</button></a>'." } ]</li>\n";<br/>
	}<br/>
}<br/>
echo '</ul>';<br/>

//etc...
?>
</code>
