<?php

require_once('./core/controller.php');
require_once('./core/sql/sql_command.php');
require_once('./core/sql/sql_result.php');
require_once('./data/user.php');
require_once('./util/element.php');
require_once('./util/footer.php');
require_once('./util/header.php');
require_once('./util/icons.php');

class TargetController extends Controller{

	private User $user;

	function show($params){
		$html = file_get_contents('./view/html/target.html');
		$html = insertHeader($html, $this->session);
		//$html = Footer::insert($html);
		$html = Icons::insert($html);
		$html = ElementUtils::insertElementStyles($html);

		$sql  = 'SELECT ID';
		$sql .= '  FROM User';
		$sql .= '  WHERE Handle = :Handle;';

		$sql_params = array(':Handle' => $params);

		$cmd = new SQLCommand($sql, $sql_params);
		$sql_result = $cmd->execute();

		if($sql_result->isEmpty()){
			header('Location: /err/show/404');
			exit();
		}else{
			$this->user = new User($sql_result->getRow()['ID']);
		}

		$sql  = 'SELECT ID';
		$sql .= '  FROM Post';
		$sql .= '  WHERE CreatorID = :ID';
		$sql .= '  ORDER BY CreationTime DESC;';

		$params = array(':ID' => $this->user->getUUID());

		$cmd = new SQLCommand($sql, $params);
		$sql_result = $cmd->execute();

		if($sql_result->isEmpty()){
			$html = str_replace('<!-- element placeholder -->', '<p>No posts available.</p>', $html);
		}else{
			$count = 0;
			do{
				$row = $sql_result->getRow();
				$html = ElementUtils::insertElement($row['ID'], $html, $user);
				$count++;
			}while($sql_result->next() && $count < 64);
		}

		echo $html;
	}

	function shoot($params){
		$sql  = 'SELECT ID';
		$sql .= '  FROM User';
		$sql .= '  WHERE Handle = :Handle;';

		$sql_params = array(':Handle' => $params);

		$cmd = new SQLCommand($sql, $sql_params);
		$sql_result = $cmd->execute();

		if($sql_result->isEmpty()){
			header('Location: /err/show/404');
			exit();
		}else{
			$this->user = new User($sql_result->getRow()['ID']);
		}

		$sql = 'INSERT INTO Shoot (Target, Archer) VALUES (:Target, :Archer);';

		$sql_params = array(':Target' => $user->getUUID(), ':Archer' => $_SESSION['userID']);

		$cmd = new SQLCommand($sql, $sql_params);
		$cmd->execute();
		header('Location: /target/show/' . $params);

	}
	
}

?>