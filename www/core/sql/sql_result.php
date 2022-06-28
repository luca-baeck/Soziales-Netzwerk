<?php

class SQLResult{
	private PDOStatement $cmd;
	private array $row;


	public function __construct(PDOStatement $cmd){
		$this->cmd = $cmd;

		$this->next();
	}


	public function isEmpty(): bool{
		return count($this->row) == 0;
	}


	public function getRow(): array{
		return $this->row;
	}

	public function next(): bool{
		$tmp = $this->cmd->fetch();
		$this->row = $tmp ? $tmp : array();
		return($tmp !== false);
	}
}

?>