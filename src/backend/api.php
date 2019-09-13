<?php
require_once "../../vendor/autoload.php";

use tbollmeier\realworld\backend\routing\Router;
use tbollmeier\realworld\backend\db\Database;

try {
    Database::connect();
} catch (Exception $e) {
    http_response_code(500);
    echo $e->getMessage();
    return;
}

$router = new Router("tbollmeier\\realworld\\backend\\controller");

$httpMethod = $_SERVER["REQUEST_METHOD"];
$url = $_SERVER["REQUEST_URI"];

try {
    $router->route($httpMethod, $url);
} catch (Exception $e) {
    http_response_code(500);
    echo $e->getMessage();
}
