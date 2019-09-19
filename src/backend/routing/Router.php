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
    
        $action = new ActionData();
        $action->name = "signIn";
        $action->httpMethod = "POST";
        $action->pattern = "api\/users\/login";
        $action->paramNames = [];
        $controller->actions[] = $action;
    
        $action = new ActionData();
        $action->name = "getCurrent";
        $action->httpMethod = "GET";
        $action->pattern = "api\/user";
        $action->paramNames = [];
        $controller->actions[] = $action;
    
        $action = new ActionData();
        $action->name = "update";
        $action->httpMethod = "PUT";
        $action->pattern = "api\/user";
        $action->paramNames = [];
        $controller->actions[] = $action;
    
        $routerData->controllers[] = $controller;

        $controller = new ControllerData();
        $controller->name = "ProfileController";
    
        $action = new ActionData();
        $action->name = "getProfile";
        $action->httpMethod = "GET";
        $action->pattern = "api\/profiles\/([^\/]+)";
        $action->paramNames = ["username"];
        $controller->actions[] = $action;
    
        $action = new ActionData();
        $action->name = "follow";
        $action->httpMethod = "POST";
        $action->pattern = "api\/profiles\/([^\/]+)\/follow";
        $action->paramNames = ["username"];
        $controller->actions[] = $action;
    
        $action = new ActionData();
        $action->name = "unfollow";
        $action->httpMethod = "DELETE";
        $action->pattern = "api\/profiles\/([^\/]+)\/follow";
        $action->paramNames = ["username"];
        $controller->actions[] = $action;
    
        $routerData->controllers[] = $controller;

        $controller = new ControllerData();
        $controller->name = "ArticleController";
    
        $action = new ActionData();
        $action->name = "create";
        $action->httpMethod = "POST";
        $action->pattern = "api\/articles";
        $action->paramNames = [];
        $controller->actions[] = $action;
    
        $routerData->controllers[] = $controller;

        $this->setUpHandlers($routerData);
    }
}