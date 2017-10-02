<?php
include_once("../app/bootstrap.php");

use Silktide\LazyBoy\Controller\FrontController;
use Silex\Provider\ServiceControllerServiceProvider;
use Silktide\LazyBoy\Provider\CorsServiceProvider;
use Silktide\LazyBoy\Provider\JsonPostServiceProvider;
use Silktide\LazyBoy\Provider\JsonErrorProvider;

/** @var Silktide\Syringe\ContainerBuilder $builder */

$configDir = realpath(__DIR__ . "/../app/config");

$frontController = new FrontController(
    $builder,
    $configDir,
    "Silex\\Application",
    [
        new ServiceControllerServiceProvider(),
        new CorsServiceProvider(),
        new JsonPostServiceProvider(),

        // If you're not using this endpoint as a JSON API, comment the line below
        new JsonErrorProvider()
    ]
);
$frontController->runApplication();
