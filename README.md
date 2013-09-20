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

/Load all the sensors<br/>
$hw->get_sensors();

//Load the status off sensors again<br/>
$hw->get_status();

?>
</code>
