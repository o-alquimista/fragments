<?php

require '../Fragments/Utility/Server/Autoloading.php';

use Fragments\Utility\Server\Autoloading\Autoloader;
use Fragments\Utility\Server\Routing\RouterController\Router;

/**
 * The entry point of the application.
 *
 * This file initializes the router and the autoloader.
 */

$autoloader = new Autoloader;
$autoloader->register();

$router = new Router;
$router->start();