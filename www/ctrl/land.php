<?php

require_once('./core/controller.php');
require_once('./core/sql/sql_command.php');
require_once('./core/sql/sql_result.php');
require_once('./util/element.php');
require_once('./util/footer.php');
require_once('./util/header.php');
require_once('./util/icons.php');

class LandController extends Controller{

	function show(){
		$html = file_get_contents('./view/html/land.html');
		$html = insertHeader($html, $this->session);
		//$html = Footer::insert($html);
		$html = Icons::insert($html);
		$html = ElementUtils::insertElementStyles($html);

		$sql  = 'SELECT ID';
		$sql .= '  FROM Post';
		$sql .= '  ORDER BY CreationTime DESC;';

		$params = array();

		$cmd = new SQLCommand($sql, $params);
		$sql_result = $cmd->execute();

		if($sql_result->isEmpty()){
			$html = str_replace('<!-- element placeholder -->', '<p>No posts available.</p>', $html);
		}else{
			$count = 0;
			do{
				$row = $sql_result->getRow();
				$html = ElementUtils::insertElement($row['ID'], $html);
				$count++;
			}while($sql_result->next() && $count < 64);
		}

		echo $html;
	}
	
}

?>