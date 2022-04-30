<?php

class SQLResult{
	private PDOStatement $cmd;
	private array $row;


	public function __construct(PDOStatement $cmd){
		$this->cmd = $cmd;

		$tmp = $cmd->fetch();
		$this->row = $tmp ? $tmp : array();
	}


	public function isEmpty(): bool{
		return count($this->row) == 0;
	}


	public function getRow(): array{
		return $this->row;
	}
}

?>