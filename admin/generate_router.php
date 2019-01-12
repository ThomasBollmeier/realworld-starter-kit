<?php
require_once "../vendor/autoload.php";

use tbollmeier\webappfound\codegen\RouterGenerator;
use tbollmeier\webappfound\codegen\GeneratorOptions;

$routingData =<<<ROUTING

controller UserController
    actions
        signUp <- post /api/users
    end
end

default action IndexController#notFound

ROUTING;

$generator = new RouterGenerator();

$options = new GeneratorOptions();
$options->namespace = "tbollmeier\\realworld\\backend\\routing";
$options->baseRouterAlias = "BaseRouter";

$routerCode = $generator->generateFromDSL(
    "Router",
    $routingData,
    $options);

$filePath = implode(DIRECTORY_SEPARATOR, [
   "..", "src", "backend", "routing", "Router.php"
]);

file_put_contents($filePath, $routerCode);
