<?php

require_once('./core/controller.php');
require_once('./core/session.php');


class LoginController extends Controller{

	public function show(?string $errorMsg){

		$loginHtml = file_get_contents('./view/html/login.html');

		if($this->session->isLoggedIn()){
			$sql  = 'SELECT Handle, Name, ProfilePicture, CreationTime';
			$sql .= '  FROM User';
			$sql .= '  WHERE ID = :ID';
		
			$params = array(':ID' => $_SESSION['userID']);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();
			$row = $sqlResult->getRow();

			if($row['ProfilePicture']){
				$pic = $row['ProfilePicture'];
			}else{
				$pic = '/static/img/preload-background.png';
			}
			$htmlSnippet  = '<h1>Logged In as...</h1>';
			$htmlSnippet .= '<img id="profile_picture" src="'. $pic .'" alt="profile picture">';
			$htmlSnippet .= '<p>'. $row['Name'] .'</p>';
			$htmlSnippet .= '<a href="/'. $row['Handle'] .'">'. $row['Name'] .'</a>';
			$htmlSnippet .= '<p class="creationDate">'. $row['CreationTime'] .'</p>';
			$apostrophe = "'";
			$htmlSnippet .= ' <button onclick="window.location.href ='. $apostrophe . '/login/logout' . $apostrophe .';" class="glow-on-hover" name="submit_login" type="submit" type="button">Log out</button>';

			$replace  = '<h1>';
			$replace .= stringBetweenTwoStrings($loginHtml, '<h1>', '</form>');
			$replace .= '</form>';

			$loginHtml = str_replace($replace, $htmlSnippet, $loginHtml);
		}
		if($errorMsg == 'failed'){
			$errorMsgHtml = '<p class="errorMessage input-invalid">invalid username or password...</p>';
			$loginHtml = str_replace('<p class="errorMessage input-invalid"></p>', $errorMsgHtml, $loginHtml);
		}
		echo($loginHtml);
  	}

	public function login($params){

		if(empty($_POST['handle_login']) or empty($_POST['password_login'])){
			header('Location: /login');
		}else{
			$sql  = 'SELECT ID';
			$sql .= '  FROM User';
			$sql .= '  WHERE Handle = :Handle';
			$sql .= ' AND Password = :Password;';

			
			$pwHash = hash('sha256', $_POST['password_login']); 
			$params = array(':Handle' => $_POST['handle_login'], ':Password' => $pwHash);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();


			if($sqlResult->isEmpty()){
				header('Location: /login/show/failed');
			}else{
				$row = $sqlResult->getRow();
				$userID = $row['ID'];
				$stayLoggedIn = isset($_POST['checkbox_login']);
				$this->session->login($userID, $stayLoggedIn);
				if(isset($_SESSION['redirect']) and !empty($_SESSION['redirect'])){
					$redUri = $_SESSION['redirect'];
					$_SESSION['redirect'] = NULL;
					header('Location: ' . $redUri);
				}else{
					header('Location: /land');
				}
			}
		}
	}

	public function logout(){
		$this->session->logout();
		header('Location: /login');
	}
}

?>


