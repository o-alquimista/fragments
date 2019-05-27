<?php

require '../Fragments/Utility/Server/Autoloading.php';

use Fragments\Utility\Server\Autoloading\Autoloader;
use Fragments\Utility\Server\Routing\Router;

/**
 * The entry point of the application.
 *
 * This file instantiates the router and the autoloader.
 */

new Autoloader;

$router = new Router;
$router->interpret();
