<?php

abstract class Controller{
    public function __construct(){

    }

    protected function reload(){
        $location = $_POST['location'];
        header("Location: $location");
    }
}

?>