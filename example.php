<?php
/*******************************************
# Author: R. Van de Voorde
# Version: 1.0
# Date: 27-10-2013
# Contact me at: free.php@wobbles.nl
#
# Example on how to use the homewziard class
#		  
********************************************/

//Load the class
require "class.homewizard.php";
$hw = new homewizard();

//Set the homewizard connection parameters
$hw->ip_address = '<IP ADDRESS HOMEWIZARD>';
$hw->password = '<PASSWORD HOMEWIZARD>';

if ($hw->handshake()) {
	echo "Homewizard is online!\n";
} else {
	echo "Homewizard not found!\n";	
	return;
}

//Load all the switches
if ($hw->get_switches()) {
	//Show the switches with their status
	echo "<br/><br/>All switches:\n";
	echo "<ul>";
	foreach ($hw->switches as $switch) {
		echo "<li>".$switch->name;
		echo "&nbsp;-&nbsp;The switch is now ".$switch->status;
		echo "</li>\n";
	}
	echo "</ul>\n";
	echo "<br/>\n";
}

//Load all the thermometers
if ($hw->get_thermometers()) {
	//Show the thermometers with their value
	echo "<br/><br/>All Thermometers:\n";
	echo "<ul>";
	foreach ($hw->thermometers as $thermometer) {
		echo "<li>".$thermometer->name;
		echo "&nbsp;-&nbsp;The temperature is now ".$thermometer->te;
		echo "</li>\n";
	}
	echo "</ul>\n";
	echo "<br/>\n";
}

//Switch on/off switch ID 0
?>
<input type=button onClick="parent.location='?action=on'" value='Switch on'>&nbsp;&nbsp;<input type=button onClick="parent.location='?action=off'" value='Switch off'>
<?php

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case 'on':			
			$hw->switch_on(0);			
			break;
		case 'off':
			$hw->switch_off(0);			
			break;	
	}

}


?>