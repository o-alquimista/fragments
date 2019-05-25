<?php

require '../Fragments/Utility/Autoloading.php';

use Fragments\Utility\Autoloading\Autoloader;
use Fragments\Utility\Routing\Router;

/**
 * The entry point of the application.
 *
 * This file instantiates the router and the autoloader.
 */

new Autoloader;

$router = new Router;
$router->interpret();