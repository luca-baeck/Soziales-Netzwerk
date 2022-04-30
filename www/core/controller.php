<?php

require_once('./core/session.php');

abstract class Controller{

	private Session $session;

	public function __construct(Session $session){
		$this->session = $session;
	}

	protected function reload(){
		$location = $_POST['location'];
		header("Location: $location");
	}
}

?>