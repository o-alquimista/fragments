<?php

/**
 * Index
 *
 * The entry point of the application.
 * This file instantiates the router
 * and the autoloader.
 */

require '../Utility/Autoloading.php';

use Fragments\Utility\Autoloading\Autoload;
use Fragments\Utility\Routing\Router;

new Autoload;

$router = new Router;
$router->interpreter();

?>