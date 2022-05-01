<?php

require_once('./config/miscellaneous.php');
require_once('./core/session.php');
require_once('./util/util.php');

abstract class Controller{

	protected Session $session;

	public function __construct(Session $session){
		$this->session = $session;
	}

	protected function reload(){
		$location = $_POST['location'];
		header("Location: $location");
	}

	public static function accessibility(string $method): string{
		return Miscellaneous::LEVEL_RANKS[0];
	}
}

?>