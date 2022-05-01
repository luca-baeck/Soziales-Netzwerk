<?php

require_once('./core/controller.php');
require_once('./core/session.php');

class RegisterController extends Controller{

	public function show()
  	{
		$html = file_get_contents('./view/html/register.html');
		echo($html);
  	}
}

?>


