<?php

require_once('./config/miscellaneous.php');
require_once('./core/session.php');
require_once('./util/string.php');
require_once('./util/debug.php');

abstract class Controller{

	protected Session $session;

	public function __construct(Session $session){
		$this->session = $session;
	}

	protected function reload(){
		$location = $_SERVER['REQUEST_URI'];
		header("Location: $location");
	}

	public static function accessibility(string $method): string{
		return Miscellaneous::LEVEL_RANKS[0];
	}
}

?>