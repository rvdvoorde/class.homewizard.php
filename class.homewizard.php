<?php

/*******************************************
# Author: R. Van de Voorde
# Version: 1.1
# Date: 29-10-2013
# Contact me at: free.php@wobbles.nl
#
# Homewizard version: 2.47
# 
# Known types (19-09-2013):
#	* switches
#	* thermometers
#	* scenes
#	* energymeters
#	* uvmeters
#	* windmeters
#	* rainmeters
#	* energylinks
#	* heatlinks
#	* somfy
#	* kakusensors
#	* cameras
#
#	Note: After each command the values $status & $version are set again.
#
#		* 27-10-2013
#			f Fixed private function _switch_on			
#		* 29-10-2013
#			+ Added function thermo_graph.
#			i It now replaces te-/+ and hu-/+ to te/hu_min and te/hu_plus
#		
********************************************/

class homewizard {

	public $ip_address = '';
	public $password = '';
	
	private $_version = '2.47'; //HW version when this class was written/last updated!
	
	
	function __construct() {

	}
	
	function __destruct() {
	
	}
	
	/* This function tries to locate the homewizard on the network */
	public function locate_hw() {
		// Returns true or false. Sets the $ip_address value.
		return $this->_locate_hw();
	}
	
	/* A handshake to get device info from the homewizard. Password is not required for this. */
	public function handshake() {
		//Returns true or false. Values $homewizard, $firmwareupdateavailable, $appupdaterequired, and $serial are set			
		return $this->_handshake();
	}
	
	/* Get the sunrise/sunset times for today */
	public function suntimes() {
		//Returns true or false. Values $sunrise & $sunset are set
		return $this->_suntimes();
	}
	
	/* Load the detailed switches data */
	public function get_switches() {
		//Returns true or false. Values are set in $switches.
		return $this->_get_switches();
	}
	
	/* Load the detailed thermometers data */
	public function get_thermometers() {
		//Returns true or false. Values are set in $thermometers.
		return $this->_get_thermometers();
	}
	
	/* Load the detailed scenes data */
	public function get_scenes() {
		//Returns true or false. Values are set in $scenes.
		return $this->_get_scenes();
	}
	
	/* Load the detailed energymeters data */
	public function get_energymeters() {
		//Returns true or false. Values are set in $thermometers.
		return $this->_get_energymeters();
	}
		
	/* Load the detailed uvmeters data */
	public function get_uvmeters() {
		//Returns true or false. Values are set in $uvmeters.
		return $this->_get_uvmeters();
	}
	
	/* Load the detailed windmeters data */
	public function get_windmeters() {
		//Returns true or false. Values are set in $windmeters.
		return $this->_get_windmeter();
	}
	
	/* Load the detailed rainmeters data */
	public function get_rainmeters() {
		//Returns true or false. Values are set in $rainmeters.
		return $this->_get_rainmeters();
	}
	
	/* Load the detailed timers data */
	public function get_timers() {
		//Returns true or false. Values are set in $timers.
		return $this->_get_timers();
	}
	
	/* Get the complete status from the homewizard (simple data) */
	public function get_status() {
		//Returns true or false. Values are set in their object ($switches, $thermometers, etc)
		return $this->_get_status();
	}	
	
	/* Get all the sensors from the homewizard (extended data) */
	public function get_sensors() {
		//Returns true or false. Values are set in their object ($switches, $thermometers, etc)
		return $this->_get_sensors();		
	}
	
	/* Switch on. Set level in case a dimmer */
	public function switch_on ($id, $level = 100) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_switch_on ($id, $level);
	}
	
	/* Switch off */
	public function switch_off($id) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_switch_off ($id);
	}
	
	/* Switch scene on */
	public function switch_scene_on($id) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_switch_scene_on ($id);
	}
	
	/* Switch scene off */
	public function switch_scene_off($id) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)		
		return $this->_switch_scene_off ($id);
	}
	
	/* Switch a somfy down */
	public function somfy_down($id) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_somfy_down ($id);
	}
	
	/* Switch a somfy up */
	public function somfy_up($id) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_somfy_up ($id);
	}
	
	/* Switch a somfy stop */
	public function somfy_stop($id) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_somfy_stop ($id);
	}
	
	/* Add a new switch */
	public function add_switch($name, $code) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_add_switch($name, $code);
	}
	
	/* Remove a switch */
	public function remove_switch($id) {
		//Returns true or false. No values are set! (Execute get_status to get the new status)
		return $this->_remove_switch($id);
	}
	
	/* Get thermometers graph data for 1 year */
	/* Valid period values: day, month, year */
	public function thermo_graph($id, $period) {
		if ($period != 'day' && $period != 'month' && $period != 'year') {
			return false;
		}
		return $this->_thermo_graph($id, $period);
	}
	
	/* Replace the text on standard options */
	public function replace_text($text) {
		if (strlen($text) == 0) {
			return 'Onbekend';
		}
		$text = str_replace('no', 'nee', $text);
		$text = str_replace('yes', 'ja', $text);
		$text = str_replace('on', 'aan', $text);
		$text = str_replace('off', 'uit', $text);		
		return $text;
	}	
	
	/* Replace the text on motion/contacts/doorbells texts */
	public function replace_sensor($text) {
		if (strlen($text) == 0) {
			return 'Onbekend';
		}
		$text = str_replace('no', 'rust', $text);
		$text = str_replace('yes', 'alarm', $text);
		$text = str_replace('on', 'alarm', $text);
		$text = str_replace('off', 'rust', $text);		
		return $text;
	}
	
	/* ----------------------------------------------
	# From here all the action takes place.
	# These functions are all defined as private.	
	#
	# Change only when you know what you are doing!
	# 
	#	
	#	
	#
	-------------------------------------------------*/
	
	private function _locate_hw() {
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);		
		socket_bind($socket, "0.0.0.0", 55555);
		
		$timeout = array('sec'=>20,'usec'=>0);
		socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO,$timeout);
		socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);
		socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
		socket_set_option($socket, SOL_SOCKET, SO_DEBUG, 0);				
		
		while(true) {
			socket_recvfrom($socket, $buf, 512, 0, $remote_ip, $remote_port);			
			if (strpos($buf,'HomeWizard') !== false) {
				$this->ip_address = $remote_ip;				
				socket_close($socket);
				return true;
			} else {
				socket_close($socket);
				return false;
			}
		}		
	}
	
	private function _handshake() {
		if (!$this->_get_data('handshake')) {		
			return false;
		}
				
		//Set all the object values
		foreach ($this->raw->response as $key=>$value) {
			$this->{$key} = new stdClass();
			$this->{$key} = $value;	
		}
		
		return true;
	}
	
	private function _suntimes() {
		if (!$this->_get_data('suntimes/today')) {
			$this->sunrise = '';
			$this->sunset = '';
			return false;
		}
		
		$this->sunrise = $this->raw->response->sunrise;
		$this->sunset = $this->raw->response->sunset;
		return true;
	}
	
	private function _get_switches() {
		if (!$this->_get_data('swlist')) {
			return false;
		}
				
		//Set all the switches values
		unset($this->switches);
		foreach ($this->raw->response as $key=>$value) {			
			$this->switches->{$key} = $value;		
		}
		
		return true;
	}
	
	private function _get_thermometers() {
		if (!$this->_get_data('telist')) {
			return false;
		}
		
		//Set all the thermometers values
		unset($this->thermometers);
		foreach ($this->raw->response as $key=>$value) {			
			$this->thermometers->{$key} = $value;		
		}
		
		return true;
	}
	
	private function _get_energymeters() {
		if (!$this->_get_data('enlist')) {
			return false;
		}
		
		//Set all the energymeters values
		unset($this->energymeters);
		foreach ($this->raw->response as $key=>$value) {
			$this->energymeters->{$key} = $value;		
		}
		
		return true;
	}
	
	private function _get_uvmeters() {  //TODO: Test, don't have these sensors
		if (!$this->_get_data('uvlist')) { 
			return false;
		}
		
		//Set all the uvmeters values
		unset($this->uvmeters);
		foreach ($this->raw->response as $key=>$value) {
			$this->uvmeters->{$key} = $value;		
		}
		
		return true;
	}
	
	private function _get_windmeters() { //TODO: Test, don't have these sensors
		if (!$this->_get_data('wilist')) { 
			return false;
		}
		
		//Set all the windmeter values
		unset($this->windmeters);
		foreach ($this->raw->response as $key=>$value) {
			$this->windmeters->{$key} = $value;		
		}
		
		return true;
	}
		
	private function _get_rainmeters() { //TODO: Test, don't have these sensors
		if (!$this->_get_data('ralist')) {
			return false;
		}
		
		//Set all the rainmeter values
		unset($this->rainmeters);
		foreach ($this->raw->response as $key=>$value) {
			$this->rainmeters->{$key} = $value;		
		}
		
		return true;
	}
	

		
	private function _get_timers() {
		if (!$this->_get_data('timers')) {
			return false;
		}
		
		//Set all the timers values
		unset($this->timers);
		foreach ($this->raw->response as $key=>$value) {
			$this->timers->{$key} = $value;		
		}
		
		return true;
	}
	
	private function _get_scenes() {
		if (!$this->_get_data('gplist')) {
			return false;
		}
		
		//Set all the scenes values
		unset($this->scenes);
		foreach ($this->raw->response as $key=>$value) {
			$this->scenes->{$key} = $value;		
		}
		
		return true;
	}
	
	private function _get_sensors() {				
		if (!$this->_get_data('get-sensors')) {
			return false;
		}
		
		//Set all the object values
		foreach ($this->raw->response as $key=>$value) {
			$this->{$key} = $value;		
		}
		
		return true;		 		
	}
	
	private function _get_status() {
		if (!$this->_get_data('get-status')) {
			return false;
		}

		//Set all the object values
		foreach ($this->raw->response as $key=>$value) {
			$this->{$key} = $value;		
		}
		
		return true;
	}
	
	private function _switch_on ($id, $level) {		
		foreach ($this->switches as $switch) {
			if ($switch->id == $id) { break;}
		}
		
		if ($switch->id != $id) {
			return false;
		}
		
		if (isset($switch->dimmer) && $switch->dimmer == 'yes') {
			return ($this->_get_data('sw/dim/'.$id.'/'.$level));
		} else {
			return ($this->_get_data('sw/'.$id.'/on'));
		}
	}
	
	private function _switch_off($id) {		
		return ($this->_get_data('sw/'.$id.'/off'));
	}
	
	private function _switch_scene_on($id) {
		return ($this->_get_data('gp/'.$id.'/on'));
	}
	
	private function _switch_scene_off($id) {
		return ($this->_get_data('gp/'.$id.'/off'));
	}
	
	private function _somfy_down($id) {		
		return ($this->_get_data('sf/'.$id.'/down'));
	}
		
	private function _somfy_up($id) {		
		return ($this->_get_data('sf/'.$id.'/up'));
	}
		
	private function _somfy_stop($id) {		
		return ($this->_get_data('sf/'.$id.'/stop'));
	}
		
	private function _add_switch($name, $code) {
		$name = substr($name, 0, 15); //Max. 15 chars long name
		return ($this->_get_data('add/'.$name.'/switch/'.$code));
	}
		
	private function _remove_switch($id) {		
		return ($this->_get_data('sw/remove/'.$id));
	}
	
	private function _thermo_graph($id, $period) {
		if ($this->_get_data('te/graph/'.$id.'/'.$period)) {
			$this->graph_year = $this->raw->response;
			return true;
		} else {
			return false;
		}
	}
	
	/* This function gets/sets the data, checks the status and sets status/version */
	private function _get_data($command) {
		$this->warning = '';
		
		//The URL to get the data
		if ($command == 'handshake') {
			$url = 'http://'.$this->ip_address.'/'.$command.'/';
		} else {
			$url = 'http://'.$this->ip_address.'/'.$this->password.'/'.$command.'/';
		}
		
		//Only use for debugging since the password is in PLAIN TEXT!!
		//echo $url.'<br/>';
		
		//Load the data in json format
		unset ($this->json);
		$this->json = file_get_contents($url);
		$this->json = str_replace("hu-", "hu_min", $this->json);
		$this->json = str_replace("te-", "te_min", $this->json);
		$this->json = str_replace("hu+", "hu_plus", $this->json);
		$this->json = str_replace("te+", "te_plus", $this->json);
		
		//Reset the last data and fill it again
		unset ($this->raw);
		$this->raw = json_decode($this->json);
		
		//Did we get the data and is all ok?
		if (isset($this->raw->status) && $this->raw->status == 'ok') {
			//Version as this class was written on?			
			if ($this->raw->version != $this->_version) {				
				$this->warning = "<b>This homewizard class was written for version ".$this->_version.". Results can be in error!</b><br/>\n";
			}
		
			$this->status = true;
			$this->version = $this->raw->version;
			$this->last_update = date('d-m-Y H:i:s');
			return true;
		} else {
			$this->status = false;
			$this->version = '';
			return false;
		}
	}	
}


?>