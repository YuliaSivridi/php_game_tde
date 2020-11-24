<?php
class Player {
	public $name;
	public $losses;
	public $played;
	function __construct($name) {
		$this->name = $name;
		$this->losses = 0;
		$this->played = 0;
	}
}

class MatchPlayer {
	public $id;
	public $name;
	public $state;
	public $next;
	function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
		$this->state = 0;
		$this->next = array();
	}
}

class Match {
	public $gridMatch;
	public $players;
}
?>