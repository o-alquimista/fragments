<?php

/**
 * Index
 *
 * The entry point of the application.
 * This file instantiates the router
 * and the autoloader.
 */

require '../Fragments/Utility/Autoloading.php';

use Fragments\Utility\Autoloading\Autoloader;
use Fragments\Utility\Routing\Router;

new Autoloader;

$router = new Router;
$router->interpreter();

?>