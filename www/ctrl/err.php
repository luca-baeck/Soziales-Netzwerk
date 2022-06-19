<?php
require_once('./core/controller.php');
require_once('./util/header.php');
require_once('./util/util.php');

class ErrController extends Controller{

    public function show($errorCode)
    {
        $errHtml = file_get_contents('./view/html/err.html');
        $errHtml = insertHeader($errHtml, $this->session);
        echo($errHtml);
    }

}
?>