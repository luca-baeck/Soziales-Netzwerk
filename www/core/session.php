<?php

require_once('./core/logger.php');
require_once('./core/uuid_factory.php');
require_once('./core/sql/db.php');
require_once('./core/sql/sql_command.php');
require_once('./core/sql/sql_result.php');
require_once('./core/utils.php');

class Session{
	private ?string $userID;
	private ?string $expirationDate;
	private ?string $token;
	private ?bool $stayLoggedIn;

	private IDFactory $idFactory;

	public function __construct(){
		ini_set('session.use_strict_mode', 1);
		session_start();

		$this->idFactory = new UUIDFactory();
		
		if(isset($_COOKIE['authentificationUser']) and isset($_COOKIE['authentificationToken'])){
			if(isset($_SESSION['userID']) and isset($_SESSION['token'])){
				if($_SESSION['userID'] = $_COOKIE['authentificationUser'] and $_SESSION['token'] = $_COOKIE['authentificationToken']){
					$this->userID = $_SESSION['userID'];
					$this->token = $_SESSION['token'];
					$this->stayLoggedIn = $_SESSION['stayLoggedIn'];
					if(isset($_COOKIE['authentificationRefresh'])){
						if($_COOKIE['authentificationRefresh']) < time()){
							$timeToExpire = time() + 3600 * 2;
							$timeToRefresh = time() + (3600);
							setcookie('authentificationRefresh', $timeToRefresh, $timeToExpire);
							setcookie('authentificationUser', $this->userID, $timeToExpire);
							setcookie('authentificationToken', $this->token, $timeToExpire);
						}
					}
				}else{
					deleteCookie('authentificationUser');
					deleteCookie('authentificationToken');
				}
			}else{
				$sql  = 'SELECT *';
				$sql .= '  FROM Session';
				$sql .= '  WHERE UserID = :UserID';
				$sql .= '    AND Token = :Token';
				$sql .= '    AND ExpirationDate < CURRENT_DATE;';
		
				$params = array(':UserID' => $_COOKIE['authentificationUser'], ':Token' => $_COOKIE['authentificationToken']);

				$cmd = new SQLCommand($sql, $params);
				$sqlResult = $cmd->execute();
				if($sqlResult->isEmpty()){
					deleteCookie('authentificationUser');
					deleteCookie('authentificationToken');
				}else{
					$row = $sqlResult->getRow();
					$this->userID = $row['UserID'];
					$_SESSION['userID'] = $this->userID;
					$this->expirationDate = $row['ExpirationDate'];
					$this->token = $row['Token'];
					$_SESSION['token'] = $this->token;
					$this->stayLoggedIn = true;
					$_SESSION['stayLoggedIn'] = $this->stayLoggedIn;
				}
			}
		}
	}

	public function isLoggedIn(): bool{
		return !empty($userID);
	}

	public function login(string $userID, bool $stayLoggedIn){
		$this->userID = $userID;
		$this->stayLoggedIn = $stayLoggedIn;
		$this->token = $this->idFactory->create();
		$datetime = new DateTime('today');
		$datetime->modify('+30 days');
		$this->expirationDate = $datetime->format('Y-m-d');
		$timeToExpire = time();
		if($stayLoggedIn){
			$sql  = 'INSERT INTO Session (UserID, Token, ExpirationDate)';
			$sql .= '  VALUES(:UserID, :Token, :ExpirationDate);';

			$params = array(':UserID' => $this->userID, ':Token' => $this->token, ':ExpirationDate' => $this->expirationDate);

			$cmd = new SQLCommand($sql, $params);
			$cmd->execute();

			$timeToExpire += 86400 * 30;
		}else{
			$timeToExpire += 3600 * 2;
			$timeToRefresh = time() + (3600);
			setcookie('authentificationRefresh', $timeToRefresh, $timeToExpire);
		}
		$_SESSION['userID'] = $this->userID;
		$_SESSION['token'] = $this->token;
		$_SESSION['stayLoggedIn'] = $this->stayLoggedIn;
		setcookie('authentificationUser', $this->userID, $timeToExpire);
		setcookie('authentificationToken', $this->token, $timeToExpire);
	}


	public function logout(){
		$cookiesToDelete = array(
			'authentificationUser',
			'authentificationToken',
			'authentificationRefresh'
		);
		
		deleteCookies($cookiesToDelete);

		if($this->stayLoggedIn){
			$sql = 'DELETE FROM Session';
			$sql .= ' WHERE UserID = :UserID';
			$sql .= '   AND Token = :Token;';
			$params = array(':UserID' => $this->userID, ':Token' => $this->token);

			$cmd = new SQLCommand($sql, $params);
			$cmd->execute();
		}
		
		session_destroy();
		setcookie("PHPSESSID", "", time() - 3600);
	}

}
