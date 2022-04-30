<?php

require_once("./core/sql/db.php");
require_once("./core/sql/result.php");

class SQL{

	private PDO $pdo;


	public function __construct(bool $canWrite = false){
		$this->pdo = DB::connect($canWrite);
	}


	public function request(String $command = ";", array $bindValues = null){
		if ($bindValues == null){
			$bindValues = array();
		}

		$cmd = $this->pdo->prepare($command);

		foreach ($bindValues as $key => $value)
		{
			$cmd->bindValue($key, $value, PDO::PARAM_STR);
		}

		$cmd->execute();
		$result = $cmd->fetchAll();
		return new Result($result);
	}


	public function post(String $command = ";", array $bindValues = null){
		if ($bindValues == null){
			$bindValues = array();
		}
		$cmd = $this->pdo->prepare($command);

		// print_r($bindValues);

		foreach ($bindValues as $key => $value)
		{
			$cmd->bindValue($key, $value, PDO::PARAM_STR);
		}
		// print_r($cmd);
		$cmd->execute();
	}

	public function transaction(array $commands, array $bindValues){
		try {

			$this->pdo->beginTransaction();

			foreach($commands as $key => $commamd){
				$cmd = $this->pdo->prepare($commamd);

				if(isset($bindValues[$key])){
					foreach($bindValues[$key] as $bind_key => $value){
						// print_r($value);
						$cmd->bindValue($bind_key, $value, PDO::PARAM_STR);
					}
				}

				$cmd->execute();
			}

			$this->pdo->commit();

		}catch (Exception $e){
			print_r($e);
			$this->pdo->rollBack();
			return false;
		}
		return true;
	}
}
