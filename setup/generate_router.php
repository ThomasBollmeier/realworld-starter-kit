<?php

require_once "../vendor/autoload.php";

use tbollmeier\webappfound\codegen\GeneratorOptions;
use tbollmeier\webappfound\codegen\RouterGenerator;

$generator = new RouterGenerator();

$options = new GeneratorOptions();
$options->namespace = "tbollmeier\\realworld\\backend\\routing";
$options->baseRouterAlias = "BaseRouter";

$className = "Router";

$controllersFile = __DIR__ . DIRECTORY_SEPARATOR . "controllers";

$classCode = $generator->generateFromDSL($className, $controllersFile, $options);

$filePath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
$filePath .= "src" . DIRECTORY_SEPARATOR . "backend" . DIRECTORY_SEPARATOR . "routing";
$filePath .= DIRECTORY_SEPARATOR . $className . ".php";

file_put_contents($filePath, $classCode);