<?php

require_once('./config/router.php');
require_once('./core/session.php');

class Router{
	private Session $session;

	public function __construct(Session $session){
		$this->session = $session;
		set_exception_handler(function (Throwable $exception) {
			$this->errorHandler(404);
		});

	}

	private function errorHandler($errorCode){
		require_once('./ctrl/err.php');
		$controller = new ErrController($this->session);
		$controller->show($errorCode); 
	}

	public function routeTo(string $uri){
		$parts = explode('/', $uri, 3);
		
		$ctrl_file = $parts[0];
		$action = count($parts) > 1 ? $parts[1] : 'show';
		$params = count($parts) > 2 ? $parts[2] : null;

		if($ctrl_file == 't'){
			$ctrl_file = 'target';
			$action = 'show';
			$params = $parts[1];
		}

		if (!in_array($ctrl_file, RouterConfig::ALL_CTRL_FILES)){
			$ctrl_file = 'target';
			$action = 'show';
			$params = $parts[0];
		}

		if(in_array($ctrl_file, RouterConfig::PUBLIC_CTRL_FILES)){
			require_once('./ctrl/' . $ctrl_file . '.php');
			$ctrl_class = strtoupper(substr($ctrl_file, 0, 1)) . substr($ctrl_file, 1) . 'Controller';
			$controller = new $ctrl_class($this->session);
			$controller->$action($params);
		}else if($this->session->isLoggedIn()){
			if(in_array($ctrl_file, RouterConfig::PRIVATE_CTRL_FILES)){
				require_once('./ctrl/' . $ctrl_file . '.php');
				$ctrl_class = strtoupper(substr($ctrl_file, 0, 1)) . substr($ctrl_file, 1) . 'Controller';
				$controller = new $ctrl_class($this->session);
				$controller->$action($params);
			}
		}else{
			$_SESSION['redirect'] = '/' . $uri;
			header('Location: /login');
		}
	}
}

?>