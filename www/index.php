<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);

require_once('./core/router.php');
require_once('./core/session.php');

$session = new Session();

$router = new Router();
$router->routeTo($_GET['uri']);

?>