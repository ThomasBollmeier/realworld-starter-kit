<?php
namespace tbollmeier\realworld\backend\routing;

use tbollmeier\webappfound\routing\Router as BaseRouter;
use tbollmeier\webappfound\routing\RouterData;
use tbollmeier\webappfound\routing\ControllerData;
use tbollmeier\webappfound\routing\ActionData;
use tbollmeier\webappfound\routing\DefaultActionData;

class Router extends BaseRouter{
    public function __construct(
        $controllerNS = "",
        $baseUrl = "")
    {
        parent::__construct([
            "controllerNS" => $controllerNS,
            "defaultCtrlAction" => "IndexController.notFound",
            "baseUrl" => $baseUrl ]);

        $routerData = new RouterData();

        $routerData->defaultAction = new DefaultActionData();
        $routerData->defaultAction->controllerName = "IndexController";
        $routerData->defaultAction->actionName = "notFound";

        $routerData->controllers = [];

        $controller = new ControllerData();
        $controller->name = "UserController";
    
        $action = new ActionData();
        $action->name = "signUp";
        $action->httpMethod = "POST";
        $action->pattern = "api\/users";
        $action->paramNames = [];
        $controller->actions[] = $action;
    
        $routerData->controllers[] = $controller;

        $this->setUpHandlers($routerData);
    }
}