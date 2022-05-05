<?php
require_once('./core/uuid_factory.php');
require_once('./core/controller.php');
require_once('./core/session.php');

class RegisterController extends Controller{

	private IDFactory $idFactory;

	public function __construct(){
		$this->idFactory = new UUIDFactory();
	}

	public function show(?string $errorMsg)
  	{
		$registerHtml = file_get_contents('./view/html/register.html');

		switch($errorMsg){
			case "failed-handle":
				$errorMsgHtml = '<p class="errorMessage handle-invalid">handle not available</p>';
				$registerHtml = str_replace('<p class="errorMessage handle-invalid"></p>', $errorMsgHtml, $registerHtml);
			case "failed":
				$errorMsgHtml = '<p class="errorMessage handle-invalid">Sorry, something did not work right...</p>';
				$registerHtml = str_replace('<p class="errorMessage handle-invalid"></p>', $errorMsgHtml, $registerHtml);
		}

		echo($registerHtml);
  	}

	public function register($params){

		if(empty($_POST['handle_register']) or empty($_POST['password_register'])){
			header('Location: /register');
		}else{

			$handle = $_POST['handle_register'];
			$pwHash = hash('sha256', $_POST['password_register']);

			$sql  = 'SELECT ID';
			$sql .= '  FROM User';
			$sql .= '  WHERE Handle = :Handle;';
			
			$params = array(':Handle' => $handle);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();

			if(!$sqlResult->isEmpty()){
				header('Location: /register/show/failed-handle');
			}else{
				$uuid = $this->idFactory->create();
				$name = $handle;

				$sql  = 'INSERT INTO User (ID, Handle, Name, Password)';
				$sql .= ' VALUES (:ID, :Handle, :Name, :Password);';
				
				$params = array(':ID' => $uuid, ':Handle' => $handle, ':Name' => $name, ':Password' => $pwHash);
	
				$cmd = new SQLCommand($sql, $params);
				$cmd->execute();

				$_SESSION['redirect'] = '/settings';
				header('Location: /login/login/'. $handle .':'. $pwHash);

			}
		}

	}

}

?>


