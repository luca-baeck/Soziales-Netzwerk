<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);

require_once('./core/router.php');

$router = new Router();
$router->routeTo($_GET['uri']);

?>