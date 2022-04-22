<?php

require_once('./utils/DB.php');

function runSQL($command){
	$pdo = DB::connect();
	$cmd = $pdo->prepare($command);
	$cmd->execute();
	return $cmd;
}


?>