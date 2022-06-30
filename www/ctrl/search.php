<?php
require_once('./core/controller.php');
require_once('./util/header.php');
require_once('./util/uuid.php');

class SearchController extends Controller{

    public function show($params)
    {
        if(!isset($_POST["search"])){
            header("Location: /land");
        }

        $searchHtml = file_get_contents('./view/html/search.html');
        $searchHtml = insertHeader($searchHtml, $this->session);

        $searchHtml = str_replace('#searchParamPlaceholder', $_POST["search"], $searchHtml);

        $searchParam = $_POST["search"];
        if(isset($_POST['sorting'])){
            $sorting = $_POST['sorting'];
        }else{
            $sorting = 1;
        }

        echo $sorting;
        echo $searchParam;

        echo($searchHtml);
    }

}
?>