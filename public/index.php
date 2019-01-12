<?php

require_once "../vendor/autoload.php";

use tbollmeier\realworld\backend\routing\RealWorldRouter;


$method = $_SERVER['REQUEST_METHOD'];
$url = explode("=", $_SERVER['QUERY_STRING'])[1];

echo $url;

$router = new RealWorldRouter("tbollmeier\\realworld\\backend\\controller");
$router->route($method, $url);
