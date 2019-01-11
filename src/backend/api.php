<?php
require_once "../../vendor/autoload.php";

use tbollmeier\realworld\backend\routing\Router;

$router = new Router("tbollmeier\\realworld\\backend\\controller");

$httpMethod = $_SERVER["REQUEST_METHOD"];
$url = $_SERVER["REQUEST_URI"];

$router->route($httpMethod, $url);