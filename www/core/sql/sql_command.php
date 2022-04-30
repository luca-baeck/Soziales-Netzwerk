<?php

require_once('./core/sql/db.php');
require_once('./core/sql/sql_result.php');


class SQLCommand{
		
	private PDOStatement $cmd;


	public function __construct(string $sql, array $params = array()){
		$pdo = DB::connect(true);
		$this->cmd = $pdo->prepare($sql);

		foreach ($params as $key => $value){
			$this->cmd->bindValue($key, $value, PDO::PARAM_STR);
		}
	}


	public function execute(): SQLResult{
		$this->cmd->execute();

		return new SQLResult($this->cmd);
	}
}
