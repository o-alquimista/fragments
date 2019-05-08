<?php

/**
 * Index
 *
 * The entry point of the application.
 * This file instantiates the router
 * and the autoloader.
 */

require '../Utility/Autoloading.php';

use Fragments\Utility\Routing\{PathFinder, Router};
use Fragments\Utility\Autoloading\Autoload;

new Autoload;

$path = new PathFinder;
$router = new Router($path);

$router->interpreter();

?>