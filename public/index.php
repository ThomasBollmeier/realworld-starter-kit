<?php

require_once "../vendor/autoload.php";

use tbollmeier\webappfound\routing\Router;


$method = $_SERVER['REQUEST_METHOD'];
$url = explode("=", $_SERVER['QUERY_STRING'])[1];

$router = new Router([
    'controllerNS' => "tbollmeier\\realworld\\backend\\controller",
    'baseUrl' => ""
]);

$controllers =<<<CONTROLLERS

controller UserController
    actions
        signUp <- post /api/users
    end
end

CONTROLLERS;

$router->registerActionsFromDSL($controllers);

$router->route($method, $url);
