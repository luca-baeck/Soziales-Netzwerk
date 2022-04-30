<?php

class Result{

    public $result;

    public function __construct($result){
        $this->result = $result;
    }

    public function getRows(int $firstRow = 0, int $numRows = PHP_INT_MAX) {
        return (new Result(array_slice($this->result, $firstRow, $numRows)));
    }

    public function getFromColumn(string $column, int $index = 0) {
        $columnArray = array_column($this->result, $column);
        if (sizeof($columnArray) == 0) {
            return new Result($columnArray);
        }
        return (new Result($columnArray[$index]));
    }

    public function getColumn(string $column, int $firstRow = 0, int $numRows = PHP_INT_MAX) {
        return (new Result(array_column($this->result, $column)))->getRows($firstRow, $numRows);
    }

    public function getNumRows() {
        if (empty($this->result)) {
            return 0;
        }
        return arrayKeyLast($this->result) + 1;
    }

    public function getResult(){
        return $this->result;
    }

    public function isEmptySet(){
        return sizeof($this->result) == 0;
    }

    public function __toString() {
        return print_r($this->result, true);
    }
}
